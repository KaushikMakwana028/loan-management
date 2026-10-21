<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Loan reminder helper (WhatsApp / SMS)
 *
 * Save this file as "UTF-8 without BOM".
 *
 * Design notes
 * - Works WITHOUT emojis: uses plain Unicode symbols (diamond, arrow, star,
 *   box lines) that survive systems which strip emojis.
 * - Loan details are shown in a WhatsApp monospace block (```), which gives
 *   a neat aligned "table" look.
 * - If your system keeps emojis intact, set LOAN_REMINDER_USE_EMOJI to true.
 */

if (!defined('LOAN_REMINDER_USE_EMOJI')) {
    define('LOAN_REMINDER_USE_EMOJI', false);
}
if (!defined('LOAN_REMINDER_COMPANY')) {
    define('LOAN_REMINDER_COMPANY', 'Kreditmitraa');
}
if (!defined('LOAN_REMINDER_EMAIL')) {
    define('LOAN_REMINDER_EMAIL', 'kreditmitraa@gmail.com');
}

/* =====================================================================
 * Small helpers
 * ===================================================================== */

if (!function_exists('lr_u')) {
    /** Unicode code point -> UTF-8 string (independent of file encoding). */
    function lr_u($cp)
    {
        return mb_convert_encoding(pack('N', $cp), 'UTF-8', 'UTF-32BE');
    }
}

if (!function_exists('lr_symbol')) {
    /**
     * Returns the decorative symbol for a role.
     * Emoji mode -> emoji, otherwise a plain Unicode symbol.
     */
    function lr_symbol($role)
    {
        $emoji = array(
            'head'    => array(0x1F514),          // bell
            'overdue' => array(0x1F6A8),          // siren
            'today'   => array(0x23F0),           // alarm clock
            'soon'    => array(0x1F4C5),          // calendar
            'section' => array(0x1F4CB),          // clipboard
            'total'   => array(0x1F4B0),          // money bag
            'pay'     => array(0x1F4B3),          // card
            'link'    => array(0x1F449),          // pointing right
            'ok'      => array(0x2705),           // check
            'help'    => array(0x1F4DE),          // phone
            'note'    => array(0x26A0, 0xFE0F),   // warning
            'thanks'  => array(0x1F64F),          // folded hands
        );
        $plain = array(
            'head'    => array(0x25C6),           // ◆
            'overdue' => array(0x25C6),           // ◆
            'today'   => array(0x25C6),           // ◆
            'soon'    => array(0x25C6),           // ◆
            'section' => array(0x25B8),           // ▸
            'total'   => array(0x2605),           // ★
            'pay'     => array(0x25B8),           // ▸
            'link'    => array(0x27A4),           // ➤
            'ok'      => array(0x25B8),           // ▸
            'help'    => array(0x25B8),           // ▸
            'note'    => array(),
            'thanks'  => array(),
        );
        $set = LOAN_REMINDER_USE_EMOJI ? $emoji : $plain;
        if (!isset($set[$role])) {
            return '';
        }
        $out = '';
        foreach ($set[$role] as $cp) {
            $out .= lr_u($cp);
        }
        return $out;
    }
}

if (!function_exists('lr_money')) {
    function lr_money($amount)
    {
        return lr_u(0x20B9) . number_format((float)$amount, 2); // ₹
    }
}

if (!function_exists('lr_clean_name')) {
    /** Removes WhatsApp formatting characters so *bold* never breaks. */
    function lr_clean_name($name, $fallback = 'Customer')
    {
        $name = trim((string)$name);
        $name = str_replace(array('*', '_', '~', '`'), '', $name);
        $name = preg_replace('/\s+/', ' ', $name);
        return $name !== '' ? $name : $fallback;
    }
}

if (!function_exists('lr_today')) {
    /** Today's date (Y-m-d) in Indian time. */
    function lr_today()
    {
        $dt = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
        return $dt->format('Y-m-d');
    }
}

if (!function_exists('lr_days_between')) {
    /** Whole days from today (IST) to $date. Negative = overdue. */
    function lr_days_between($date)
    {
        $tz    = new DateTimeZone('Asia/Kolkata');
        $today = new DateTime(lr_today(), $tz);
        $due   = new DateTime(date('Y-m-d', strtotime($date)), $tz);
        return (int)$today->diff($due)->format('%r%a');
    }
}

if (!function_exists('lr_divider')) {
    function lr_divider()
    {
        return str_repeat(lr_u(0x2501), 18); // ━━━━━━━━━━━━━━━━━━
    }
}

if (!function_exists('lr_header')) {
    /**
     * Banner:
     *  ━━━━━━━━━━━━━━━━━━
     *  ◆ *TITLE* ◆
     *  *Company*
     *  ━━━━━━━━━━━━━━━━━━
     */
    function lr_header($role, $title, $company)
    {
        $sym = lr_symbol($role);
        $h  = lr_divider() . "\n";
        $h .= $sym . ' *' . $title . '* ' . $sym . "\n";
        $h .= '*' . $company . "*\n";
        $h .= lr_divider() . "\n\n";
        return $h;
    }
}

if (!function_exists('lr_section')) {
    /** Section title, e.g. "▸ *LOAN DETAILS*" */
    function lr_section($role, $title)
    {
        return lr_symbol($role) . ' *' . $title . '*' . "\n";
    }
}

if (!function_exists('lr_table')) {
    /**
     * Aligned key/value rows inside a WhatsApp monospace block.
     * @param array $rows array(array('Label', 'Value'), ...)
     */
    function lr_table($rows)
    {
        $w = 0;
        foreach ($rows as $r) {
            $w = max($w, strlen($r[0]));
        }
        $out = "```\n";
        foreach ($rows as $r) {
            $out .= str_pad($r[0], $w, ' ') . ' : ' . $r[1] . "\n";
        }
        $out .= "```\n";
        return $out;
    }
}

/* =====================================================================
 * Message builder
 * ===================================================================== */

if (!function_exists('build_loan_reminder_message')) {
    /**
     * Builds a clean, professional WhatsApp/SMS reminder message
     * for Overdue, Due Today and Upcoming loans.
     *
     * @param object|array $loan
     * @param string       $target 'borrower' | 'ref1' | 'ref2'
     * @return string
     */
    function build_loan_reminder_message($loan, $target = 'borrower')
    {
        $loan          = (object)$loan;
        $company       = LOAN_REMINDER_COMPANY;
        $email         = LOAN_REMINDER_EMAIL;
        $borrower_name = lr_clean_name(isset($loan->borrower_name) ? $loan->borrower_name : '', 'Customer');
        $loan_id       = isset($loan->id) ? $loan->id : (isset($loan->loan_id) ? $loan->loan_id : '');
        $principal     = (float)(isset($loan->amount) ? $loan->amount : 0);
        $due_charges   = (float)(isset($loan->due_charges) ? $loan->due_charges : 0);

        $total_pay = (float)(isset($loan->total_payable) ? $loan->total_payable : 0);
        if ($total_pay <= 0) {
            $total_pay = $principal + $due_charges;
        }

        $effective_due = !empty($loan->effective_due_date)
            ? $loan->effective_due_date
            : (!empty($loan->due_date) ? $loan->due_date : lr_today());

        $days_diff = isset($loan->days_diff) ? (int)$loan->days_diff : lr_days_between($effective_due);
        $due_date  = date('d M Y', strtotime($effective_due));

        /* ---------------- References (no amounts disclosed) ---------------- */
        if ($target === 'ref1' || $target === 'ref2') {
            $ref_raw  = ($target === 'ref1')
                ? (isset($loan->reference_name_1) ? $loan->reference_name_1 : '')
                : (isset($loan->reference_name_2) ? $loan->reference_name_2 : '');
            $ref_name = lr_clean_name($ref_raw, 'Sir/Madam');

            $m  = lr_header('head', 'REQUEST FOR ASSISTANCE', $company);
            $m .= "Dear *{$ref_name}*,\n\n";
            $m .= "Greetings from {$company}.\n\n";
            $m .= "*{$borrower_name}* has listed you as a reference on their account with us. ";
            $m .= "We have been trying to reach them regarding a pending matter but could not connect.\n\n";
            $m .= "Could you kindly ask them to contact us at the earliest?\n\n";
            $m .= lr_section('help', 'CONTACT US');
            $m .= "Reply here or email {$email}\n\n";
            $m .= "_This message is for information only. No payment is required from you._\n\n";
            $m .= "Thank you for your help.\n";
            $m .= "*{$company} Customer Care*";
            return trim($m);
        }

        /* ---------------- Borrower: OVERDUE ---------------- */
        if ($days_diff < 0) {
            $d        = abs($days_diff);
            $day_word = $d === 1 ? 'day' : 'days';

            $rows = array(
                array('Principal',    lr_money($principal)),
                array('Due date',     $due_date),
                array('Overdue by',   $d . ' ' . $day_word),
            );
            if ($due_charges > 0) {
                $rows[] = array('Late charges', lr_money($due_charges));
            }

            $m  = lr_header('overdue', 'OVERDUE LOAN NOTICE', $company);
            $m .= "Dear *{$borrower_name}*,\n\n";
            $m .= "Your personal loan is *{$d} {$day_word} overdue*. ";
            $m .= "Please clear the outstanding amount today to avoid further charges.\n\n";

            $m .= lr_section('section', 'LOAN DETAILS');
            $m .= lr_table($rows);
            $m .= lr_symbol('total') . ' *TOTAL DUE: ' . lr_money($total_pay) . "*\n\n";

            $m .= "> " . lr_symbol('note') . "*Please note:* continued non-payment may attract additional late charges and may be reported to credit bureaus, which can affect your credit score and future loan eligibility.\n\n";

            $m .= lr_section('pay', 'HOW TO PAY');
            $m .= "Please open the *{$company} App*, go to *My Loans* and pay your outstanding dues securely via UPI, GPay, PhonePe, or Netbanking.\n\n";

            $m .= lr_section('ok', 'ALREADY PAID?');
            $m .= "Reply with your payment screenshot or UTR number.\n\n";

            $m .= lr_section('help', 'NEED HELP?');
            $m .= "Reply here or email {$email}\n\n";

            $m .= "Regards,\n";
            $m .= "*Collections Team*\n";
            $m .= "*{$company}*";
            return trim($m);
        }

        /* ---------------- Borrower: DUE TODAY ---------------- */
        if ($days_diff === 0) {
            $rows = array(
                array('Principal', lr_money($principal)),
                array('Due date',  'Today, ' . $due_date),
            );
            if ($due_charges > 0) {
                $rows[] = array('Charges', lr_money($due_charges));
            }

            $m  = lr_header('today', 'PAYMENT DUE TODAY', $company);
            $m .= "Dear *{$borrower_name}*,\n\n";
            $m .= "This is a friendly reminder that your loan repayment is *due today* ({$due_date}).\n\n";

            $m .= lr_section('section', 'REPAYMENT DETAILS');
            $m .= lr_table($rows);
            $m .= lr_symbol('total') . ' *TOTAL PAYABLE: ' . lr_money($total_pay) . "*\n\n";

            $m .= "> Paying before midnight helps you avoid late charges and keeps your credit profile healthy.\n\n";

            $m .= lr_section('pay', 'HOW TO PAY');
            $m .= "Please open the *{$company} App*, go to *My Loans* and pay your due amount securely before midnight.\n\n";

            $m .= lr_section('ok', 'ALREADY PAID?');
            $m .= "Please reply with your payment receipt. Thank you!\n\n";

            $m .= "Warm regards,\n";
            $m .= "*Accounts Team*\n";
            $m .= "*{$company}*";
            return trim($m);
        }

        /* ---------------- Borrower: UPCOMING ---------------- */
        $day_word = $days_diff === 1 ? 'day' : 'days';

        $rows = array(
            array('Principal', lr_money($principal)),
            array('Due date',  $due_date),
            array('Time left', $days_diff . ' ' . $day_word),
        );

        $m  = lr_header('soon', 'UPCOMING PAYMENT REMINDER', $company);
        $m .= "Dear *{$borrower_name}*,\n\n";
        $m .= "Greetings from {$company}! Your loan repayment is due in *{$days_diff} {$day_word}*.\n\n";

        $m .= lr_section('section', 'LOAN SUMMARY');
        $m .= lr_table($rows);
        $m .= lr_symbol('total') . ' *TOTAL DUE: ' . lr_money($total_pay) . "*\n\n";

        $m .= "> Keep sufficient balance in your account, or pay early directly through the app.\n\n";

        $m .= lr_section('pay', 'HOW TO PAY');
        $m .= "Open the *{$company} App*, visit *My Loans* and complete your payment easily via UPI or Netbanking.\n\n";

        $m .= "Thank you for choosing {$company}.\n\n";
        $m .= "Best regards,\n";
        $m .= "*Customer Care Team*\n";
        $m .= "*{$company}*";
        return trim($m);
    }
}

/* =====================================================================
 * WhatsApp link
 * ===================================================================== */

if (!function_exists('build_whatsapp_link')) {
    /**
     * Builds a WhatsApp click-to-chat URL (UTF-8 safe).
     *
     * @param string $mobile
     * @param string $message
     * @return string
     */
    function build_whatsapp_link($mobile, $message)
    {
        $clean = preg_replace('/\D/', '', (string)$mobile);
        if ($clean === '' || $clean === null) {
            return '#';
        }

        // Normalise Indian numbers: 0XXXXXXXXXX -> XXXXXXXXXX -> 91XXXXXXXXXX
        if (strlen($clean) === 11 && $clean[0] === '0') {
            $clean = substr($clean, 1);
        }
        if (strlen($clean) === 10) {
            $clean = '91' . $clean;
        }

        if (function_exists('mb_check_encoding') && !mb_check_encoding($message, 'UTF-8')) {
            $message = mb_convert_encoding($message, 'UTF-8', 'auto');
        }

        return 'https://wa.me/' . $clean . '?text=' . rawurlencode($message);
    }
}
