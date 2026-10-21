(function () {
	document.addEventListener("DOMContentLoaded", function () {
		injectTablePaginationStyles();

		// 1. Process standard tables
		var tables = document.querySelectorAll("table");
		tables.forEach(function (table) {
			if (table.id === "contactsTable" || table.hasAttribute("data-no-pagination")) return;

			var tbody = table.querySelector("tbody");
			if (!tbody) return;
			var rows = Array.from(tbody.querySelectorAll("tr")).filter(
				function (row) {
					return !isEmptyStateRow(row);
				},
			);
			if (rows.length === 0) return;

			// Skip calendar, datepicker, or helper tables
			if (table.closest(".datepicker") || table.closest(".calendar")) return;

			initPagination(table, rows, "table");
		});

		// 2. Process custom lists (opportunities, notifications, returns, investments cards)
		var customLists = document.querySelectorAll(
			".notif-list, .opportunity-list, .notification-list, .repayment-list, .funding-list",
		);
		customLists.forEach(function (list) {
			var items = Array.from(list.children).filter(function (child) {
				return (
					!child.classList.contains("no-records") &&
					!child.classList.contains("no-search-results")
				);
			});
			if (items.length === 0) return;

			initPagination(list, items, "list");
		});
	});

	function getPageRange(current, total) {
		if (total <= 1) return [1];
		if (total === 2) return [1, 2];
		if (total === 3) return [1, 2, 3];

		if (current === 1) {
			return [1, 2, 3, "...", total];
		}

		if (current === total) {
			return [1, "...", total - 2, total - 1, total];
		}

		// Middle page
		var range = [1];

		if (current - 1 > 2) {
			range.push("...");
		}

		var start = current - 1;
		var end = current + 1;
		for (var p = start; p <= end; p++) {
			if (p >= 1 && p <= total) {
				range.push(p);
			}
		}

		if (current + 1 < total - 1) {
			range.push("...");
		}

		range.push(total);

		// Deduplicate
		var uniqueRange = [];
		range.forEach(function (item) {
			if (uniqueRange[uniqueRange.length - 1] === "..." && item === "...") {
				return;
			}
			if (uniqueRange.indexOf(item) === -1 || item === "...") {
				uniqueRange.push(item);
			}
		});

		// Remove '...' if it sits between consecutive numbers
		var finalRange = [];
		for (var i = 0; i < uniqueRange.length; i++) {
			if (uniqueRange[i] === "...") {
				var prev = uniqueRange[i - 1];
				var next = uniqueRange[i + 1];
				if (
					typeof prev === "number" &&
					typeof next === "number" &&
					next - prev === 1
				) {
					continue;
				}
			}
			finalRange.push(uniqueRange[i]);
		}

		return finalRange;
	}

	function injectTablePaginationStyles() {
		if (document.getElementById("js-table-pagination-styles")) return;

		var style = document.createElement("style");
		style.id = "js-table-pagination-styles";
		style.textContent = `
            .js-table-controls-bar {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                align-items: center;
                justify-content: space-between;
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
                padding: 10px 14px;
                margin: 0;
                background: #ffffff;
                border-bottom: 1px solid #edf2f7;
            }

            .js-table-search-wrap {
                position: relative;
                flex: 1 1 200px;
                min-width: 0;
                max-width: 100%;
                box-sizing: border-box;
            }

            .js-table-filter-wrap {
                position: relative;
                flex: 0 1 auto;
                min-width: 0;
                max-width: 100%;
                box-sizing: border-box;
            }

            .js-table-search-wrap::before {
                content: '';
                position: absolute;
                left: 14px;
                top: 50%;
                width: 14px;
                height: 14px;
                transform: translateY(-50%);
                background: #64748b;
                -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='black' stroke-width='2.4'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z'/%3E%3C/svg%3E") center / contain no-repeat;
                mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='black' stroke-width='2.4'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z'/%3E%3C/svg%3E") center / contain no-repeat;
                pointer-events: none;
            }

            .js-table-filter-wrap::before {
                content: '';
                position: absolute;
                left: 14px;
                top: 50%;
                width: 14px;
                height: 14px;
                transform: translateY(-50%);
                background: #64748b;
                -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='black' stroke-width='2.4'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M3 5h18M6 12h12M10 19h4'/%3E%3C/svg%3E") center / contain no-repeat;
                mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='black' stroke-width='2.4'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M3 5h18M6 12h12M10 19h4'/%3E%3C/svg%3E") center / contain no-repeat;
                pointer-events: none;
            }

            .js-table-search-input,
            .js-table-status-select {
                width: 100%;
                height: 38px;
                border: 1px solid #cbd5e1;
                border-radius: 9px;
                background: #ffffff;
                color: #0f172a;
                font-size: 13px;
                font-weight: 500;
                outline: none;
                box-sizing: border-box;
                transition: border-color .15s ease, box-shadow .15s ease;
            }

            .js-table-search-input {
                padding: 0 14px 0 38px;
            }

            .js-table-status-select {
                min-width: 140px;
                padding: 0 32px 0 38px;
                cursor: pointer;
                appearance: none;
                background-image: linear-gradient(45deg, transparent 50%, #64748b 50%), linear-gradient(135deg, #64748b 50%, transparent 50%);
                background-position: calc(100% - 15px) 16px, calc(100% - 11px) 16px;
                background-size: 5px 5px, 5px 5px;
                background-repeat: no-repeat;
            }

            .js-table-search-input::placeholder {
                color: #94a3b8;
                font-weight: 400;
            }

            .js-table-search-input:focus,
            .js-table-status-select:focus {
                background: #fff;
                border-color: #2563eb;
                box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
            }

            .js-table-scroll-wrap {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
            }

            .js-pagination-bar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
                gap: 10px;
                padding: 10px 14px;
                border-top: 1px solid #edf2f7;
                background: #fff;
                flex-wrap: wrap;
            }

            .js-pagination-info {
                font-size: 12px;
                color: #64748b;
                font-weight: 500;
            }

            .js-pagination-links {
                display: flex;
                gap: 5px;
                align-items: center;
                flex-wrap: wrap;
            }

            @media (max-width: 768px) {
                .js-table-controls-bar {
                    flex-direction: column;
                    align-items: stretch;
                    padding: 8px 10px;
                    gap: 8px;
                }

                .js-table-search-wrap,
                .js-table-filter-wrap {
                    width: 100%;
                    flex: 1 1 100%;
                }

                .js-table-status-select {
                    min-width: 100% !important;
                    width: 100% !important;
                }

                .js-pagination-bar {
                    flex-direction: column;
                    align-items: center;
                    padding: 8px 10px;
                    gap: 6px;
                }

                .js-pagination-info {
                    font-size: 11.5px;
                    text-align: center;
                }

                .js-pagination-links {
                    width: 100%;
                    justify-content: center;
                    gap: 4px;
                }
            }
        `;
		document.head.appendChild(style);
	}

	function isEmptyStateRow(row) {
		return (
			row.classList.contains("no-records") ||
			row.classList.contains("no-search-results") ||
			!!row.querySelector(".no-records, .wr-no-records") ||
			(row.children.length === 1 && row.children[0].hasAttribute("colspan"))
		);
	}

	function initPagination(container, items, type) {
		var pageSize = 5;
		var currentPage = 1;
		var filteredItems = items;

		if (container.parentNode) {
			container.parentNode.classList.add("js-table-parent");
		}

		// For tables, wrap only the <table> in its own horizontal-scroll
		// container so search/filter/pagination never get pushed off-screen
		var scrollAnchor = container;
		if (type === "table") {
			var scrollWrap = document.createElement("div");
			scrollWrap.className = "js-table-scroll-wrap";
			container.parentNode.insertBefore(scrollWrap, container);
			scrollWrap.appendChild(container);
			scrollAnchor = scrollWrap;
		}

		// Create controls wrapper
		var controlsBar = document.createElement("div");
		controlsBar.className = "js-table-controls-bar";

		var searchWrap = document.createElement("div");
		searchWrap.className = "js-table-search-wrap";

		var searchInput = document.createElement("input");
		searchInput.type = "text";
		searchInput.placeholder = "Search records...";
		searchInput.className = "js-table-search-input";
		searchWrap.appendChild(searchInput);
		controlsBar.appendChild(searchWrap);

		// Auto-detect status options from badges/pills
		var statusSet = new Set();
		items.forEach(function (item) {
			var statusEl = item.querySelector(
				".badge, .wr-badge, .status-pill, .status, [class*='status-badge']",
			);
			if (statusEl) {
				var statusText = statusEl.textContent.trim().toUpperCase();
				if (statusText) statusSet.add(statusText);
			}
		});

		var statusSelect = null;
		if (statusSet.size > 0) {
			var filterWrap = document.createElement("div");
			filterWrap.className = "js-table-filter-wrap";

			statusSelect = document.createElement("select");
			statusSelect.className = "js-table-status-select";

			var allOption = document.createElement("option");
			allOption.value = "";
			allOption.textContent = "All Statuses";
			statusSelect.appendChild(allOption);

			statusSet.forEach(function (status) {
				var option = document.createElement("option");
				option.value = status;
				option.textContent = status.charAt(0) + status.slice(1).toLowerCase();
				statusSelect.appendChild(option);
			});
			filterWrap.appendChild(statusSelect);
			controlsBar.appendChild(filterWrap);
		}

		// Insert controls before the container/table (or its scroll wrapper)
		scrollAnchor.parentNode.insertBefore(controlsBar, scrollAnchor);

		// Create pagination controls wrapper
		var paginationBar = document.createElement("div");
		paginationBar.className = "js-pagination-bar";

		var paginationInfo = document.createElement("span");
		paginationInfo.className = "js-pagination-info";
		paginationBar.appendChild(paginationInfo);

		var paginationLinks = document.createElement("div");
		paginationLinks.className = "js-pagination-links";
		paginationBar.appendChild(paginationLinks);

		// Insert pagination bar after the container/table (or its scroll wrapper)
		scrollAnchor.parentNode.insertBefore(
			paginationBar,
			scrollAnchor.nextSibling,
		);

		// Core function to filter items
		function applyFilters() {
			var query = searchInput.value.toLowerCase().trim();
			var selectedStatus = statusSelect ? statusSelect.value : "";

			filteredItems = items.filter(function (item) {
				var text = item.textContent.toLowerCase();
				var matchesSearch = text.indexOf(query) !== -1;

				var matchesStatus = true;
				if (selectedStatus) {
					var statusEl = item.querySelector(
						".badge, .wr-badge, .status-pill, .status, [class*='status-badge']",
					);
					var itemStatus = statusEl
						? statusEl.textContent.trim().toUpperCase()
						: "";
					matchesStatus = itemStatus === selectedStatus;
				}

				return matchesSearch && matchesStatus;
			});

			currentPage = 1;
			render();
		}

		// Attach listeners
		searchInput.addEventListener("input", applyFilters);
		if (statusSelect) {
			statusSelect.addEventListener("change", applyFilters);
		}

		// Core function to render page
		function render() {
			var totalItems = filteredItems.length;
			var totalPages = Math.ceil(totalItems / pageSize) || 1;

			if (currentPage > totalPages) currentPage = totalPages;
			if (currentPage < 1) currentPage = 1;

			var startIndex = (currentPage - 1) * pageSize;
			var endIndex = Math.min(startIndex + pageSize, totalItems);

			// Hide all items, show only current page items
			items.forEach(function (item) {
				item.style.display = "none";
			});

			filteredItems.slice(startIndex, endIndex).forEach(function (item) {
				if (type === "table") {
					item.style.display = "table-row";
				} else {
					item.style.display = "";
				}
			});

			// Handle Empty Search/Filter results
			if (type === "table") {
				var tbody = container.querySelector("tbody");
				var noRecordsTr = tbody.querySelector(".no-search-results");
				if (totalItems === 0) {
					if (!noRecordsTr) {
						noRecordsTr = document.createElement("tr");
						noRecordsTr.className = "no-search-results";
						var tdCount = container.querySelector("thead tr")
							? container.querySelectorAll("thead tr th").length
							: 10;
						noRecordsTr.innerHTML =
							'<td colspan="' +
							tdCount +
							'" style="text-align: center; padding: 32px; color: #64748b;">No matching records found.</td>';
						tbody.appendChild(noRecordsTr);
					} else {
						noRecordsTr.style.display = "table-row";
					}
				} else if (noRecordsTr) {
					noRecordsTr.style.display = "none";
				}
			} else {
				var noRecordsDiv = container.querySelector(".no-search-results");
				if (totalItems === 0) {
					if (!noRecordsDiv) {
						noRecordsDiv = document.createElement("div");
						noRecordsDiv.className = "no-search-results";
						noRecordsDiv.style.cssText =
							"text-align: center; padding: 32px; color: #64748b; width: 100%; border: 1px dashed #cbd5e1; border-radius: 12px;";
						noRecordsDiv.textContent = "No matching records found.";
						container.appendChild(noRecordsDiv);
					} else {
						noRecordsDiv.style.display = "block";
					}
				} else if (noRecordsDiv) {
					noRecordsDiv.style.display = "none";
				}
			}

			// Update pagination info text
			if (totalItems === 0) {
				paginationInfo.textContent = "Showing 0 to 0 of 0 items";
			} else {
				paginationInfo.textContent =
					"Showing " +
					(startIndex + 1) +
					" to " +
					endIndex +
					" of " +
					totalItems +
					" items";
			}

			// Render pagination links
			paginationLinks.innerHTML = "";

			// Prev button
			var prevBtn = document.createElement("button");
			prevBtn.type = "button";
			prevBtn.textContent = "Prev";
			stylePaginationButton(prevBtn, currentPage === 1);
			if (currentPage > 1) {
				prevBtn.addEventListener("click", function () {
					currentPage--;
					render();
				});
			}
			paginationLinks.appendChild(prevBtn);

			// Page links
			var pages = getPageRange(currentPage, totalPages);
			pages.forEach(function (page) {
				if (page === "...") {
					var dots = document.createElement("span");
					dots.textContent = "...";
					dots.style.cssText =
						"color: #94a3b8; padding: 0 4px; font-weight: 600;";
					paginationLinks.appendChild(dots);
				} else {
					var pageBtn = document.createElement("button");
					pageBtn.type = "button";
					pageBtn.textContent = page;
					stylePaginationButton(pageBtn, false, page === currentPage);
					pageBtn.addEventListener("click", function () {
						currentPage = page;
						render();
					});
					paginationLinks.appendChild(pageBtn);
				}
			});

			// Next button
			var nextBtn = document.createElement("button");
			nextBtn.type = "button";
			nextBtn.textContent = "Next";
			stylePaginationButton(nextBtn, currentPage === totalPages);
			if (currentPage < totalPages) {
				nextBtn.addEventListener("click", function () {
					currentPage++;
					render();
				});
			}
			paginationLinks.appendChild(nextBtn);
		}

		function stylePaginationButton(btn, isDisabled, isActive) {
			btn.style.cssText =
				"padding: 6px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13.5px; font-weight: 600; cursor: pointer; transition: all 0.15s ease; outline: none;";
			if (isDisabled) {
				btn.style.opacity = "0.4";
				btn.style.cursor = "not-allowed";
				btn.style.background = "#f1f5f9";
				btn.style.color = "#94a3b8";
			} else if (isActive) {
				btn.style.background = "#1e293b";
				btn.style.color = "#fff";
				btn.style.borderColor = "#1e293b";
			} else {
				btn.style.background = "#fff";
				btn.style.color = "#475569";
				btn.style.borderColor = "#cbd5e1";
				btn.addEventListener("mouseenter", function () {
					btn.style.background = "#f8fafc";
					btn.style.color = "#0f172a";
				});
				btn.addEventListener("mouseleave", function () {
					btn.style.background = "#fff";
					btn.style.color = "#475569";
				});
			}
		}

		render();
	}
})();
