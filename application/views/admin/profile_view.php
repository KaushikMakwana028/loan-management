<?php
$profile = $admin ?? NULL;
$role_names = [0 => 'User', 1 => 'Admin', 2 => 'Investor'];
$photo = $profile && !empty($profile->profile_image) ? base_url($profile->profile_image) : '';
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .profile-head {
        background: #fff;
        border: 1px solid #e8eef6;
        border-radius: 22px;
        padding: 26px;
        display: flex;
        align-items: center;
        gap: 22px;
        box-shadow: 0 14px 40px rgba(22, 34, 51, .07);
    }

    .profile-photo {
        width: 108px;
        height: 108px;
        border-radius: 28px;
        background: #eaf1ff;
        color: #2563eb;
        display: grid;
        place-items: center;
        font-size: 42px;
        font-weight: 700;
        overflow: hidden;
        flex: none;
        position: relative;
    }

    .profile-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .camera-btn {
        position: absolute;
        right: 8px;
        bottom: 8px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #2563eb;
        color: #fff;
        display: grid;
        place-items: center;
        font-size: 14px;
        border: 3px solid #fff;
    }

    .profile-head h1 {
        margin: 0 0 8px;
        font-size: 30px;
        color: #172033;
    }

    .profile-head p {
        margin: 0;
        color: #65758b;
    }

    .profile-form {
        margin-top: 24px;
        background: #fff;
        border: 1px solid #e8eef6;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 14px 40px rgba(22, 34, 51, .07);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    .full {
        grid-column: 1/-1;
    }

    .section-title {
        grid-column: 1/-1;
        margin-top: 8px;
        padding-top: 18px;
        border-top: 1px solid #eef3f8;
        color: #2563eb;
        font-weight: 700;
    }

    label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #172033;
    }

    input,
    select,
    textarea {
        width: 100%;
        border: 1px solid #dbe3ef;
        border-radius: 12px;
        padding: 13px 14px;
        font-size: 15px;
        outline: none;
        background: #fff;
    }

    textarea {
        min-height: 92px;
        resize: vertical;
    }

    input:focus,
    select:focus,
    textarea:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);
    }

    .btn {
        border: 0;
        border-radius: 12px;
        background: #2563eb;
        color: #fff;
        padding: 14px 22px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
    }

    @media(max-width:860px) {
        .profile-head {
            align-items: flex-start;
            flex-direction: column
        }

        .form-grid {
            grid-template-columns: 1fr
        }

        .btn {
            width: 100%
        }
    }
</style>

<section class="profile-head">
    <div class="profile-photo">
        <?php if ($photo): ?><img src="<?php echo $photo; ?>" alt="Profile"><?php else: ?><?php echo strtoupper(substr($profile->name ?? 'A', 0, 1)); ?><?php endif; ?>
            <span class="camera-btn">C</span>
    </div>
    <div>
        <h1><?php echo html_escape($profile->name ?? 'Admin'); ?></h1>
        <p>Update your admin profile and account details here.</p>
    </div>
</section>

<?php echo form_open_multipart('admin/profile/update', ['class' => 'profile-form']); ?>
<div class="form-grid">
    <div class="section-title">Personal Details</div>
    <div>
        <label>Name</label>
        <input type="text" name="name" value="<?php echo html_escape($profile->name ?? ''); ?>" required>
    </div>
    <div>
        <label>Mobile Number</label>
        <input type="text" name="mobile" value="<?php echo html_escape($profile->mobile ?? ''); ?>" required>
    </div>
    <div>
        <label>Email</label>
        <input type="email" name="email" value="<?php echo html_escape($profile->email ?? ''); ?>" required>
    </div>
    <div>
        <label>Profile Image</label>
        <input type="file" name="profile_image" accept="image/*">
    </div>
    <div class="full">
        <label>Address</label>
        <textarea name="address" required><?php echo html_escape($profile->address ?? ''); ?></textarea>
    </div>

    <div class="section-title">KYC Details</div>
    <div>
        <label>Aadhaar Number</label>
        <input type="text" name="aadhaar_number" value="<?php echo html_escape($profile->aadhaar_number ?? ''); ?>" required>
    </div>
    <div>
        <label>Aadhaar Photo</label>
        <input type="file" name="aadhaar_photo" accept="image/*">
    </div>
    <div>
        <label>PAN Number</label>
        <input type="text" name="pan_number" value="<?php echo html_escape($profile->pan_number ?? ''); ?>" required>
    </div>
    <div>
        <label>PAN Photo</label>
        <input type="file" name="pan_photo" accept="image/*">
    </div>

    <div class="section-title">Bank Details</div>
    <div>
        <label>Account Holder Name</label>
        <input type="text" name="account_holder_name" value="<?php echo html_escape($profile->account_holder_name ?? ''); ?>" required>
    </div>
    <div>
        <label>Bank Name</label>
        <input type="text" name="bank_name" value="<?php echo html_escape($profile->bank_name ?? ''); ?>" required>
    </div>
    <div>
        <label>Account Number</label>
        <input type="text" name="account_number" value="<?php echo html_escape($profile->account_number ?? ''); ?>" required>
    </div>
    <div>
        <label>IFSC Code</label>
        <input type="text" name="ifsc_code" value="<?php echo html_escape($profile->ifsc_code ?? ''); ?>" required>
    </div>
    <div>
        <label>Account Type</label>
        <select name="account_type" required>
            <option value="">Select Type</option>
            <?php foreach (['Savings', 'Current', 'Salary', 'Other'] as $type): ?>
                <option value="<?php echo $type; ?>" <?php echo (($profile->account_type ?? '') === $type) ? 'selected' : ''; ?>><?php echo $type; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label>Branch Name</label>
        <input type="text" name="branch_name" value="<?php echo html_escape($profile->branch_name ?? ''); ?>" required>
    </div>
    <div class="full">
        <button class="btn" type="submit">Update Profile</button>
    </div>
</div>
<?php echo form_close(); ?>

<?php if ($this->session->flashdata('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: <?php echo json_encode($this->session->flashdata('success')); ?>
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: <?php echo json_encode($this->session->flashdata('error')); ?>
        });
    </script>
<?php endif; ?>