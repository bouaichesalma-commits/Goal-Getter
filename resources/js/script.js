// // TaskFlow interactive JS
// // Save as resources/js/taskflow.js and import in resources/js/app.js
// // e.g. in app.js: import './taskflow.js';

// (() => {
//   // Helpers
//   const qs = (s, el = document) => el.querySelector(s);
//   const qsa = (s, el = document) => Array.from(el.querySelectorAll(s));

//   /* ---------- State & Persistence ---------- */
//   const STORAGE_KEY = 'taskflow.tasks.v1';

//   // Load tasks from localStorage or default one
//   function loadTasks() {
//     try {
//       const raw = localStorage.getItem(STORAGE_KEY);
//       if (!raw) return getDefaultTasks();
//       const parsed = JSON.parse(raw);
//       if (!Array.isArray(parsed)) return getDefaultTasks();
//       return parsed;
//     } catch (e) {
//       console.warn('Failed to load tasks, using defaults', e);
//       return getDefaultTasks();
//     }
//   }

//   function saveTasks(tasks) {
//     try {
//       localStorage.setItem(STORAGE_KEY, JSON.stringify(tasks));
//     } catch (e) {
//       console.warn('Failed to save tasks', e);
//     }
//   }

//   function getDefaultTasks() {
//     return [
//       {
//         id: Date.now(),
//         title: 'Task New',
//         desc: 'ASAP',
//         priority: 'low', // low | medium | high
//         due: '2025-05-01',
//         created: '2025-04-29',
//         done: false
//       }
//     ];
//   }

//   /* ---------- DOM Rendering ---------- */
//   const tasksRootSelector = '.tasks-list';
//   const placeholderSelector = '.add-new-placeholder';

//   function createTaskCard(task) {
//     // Build article.task-card (same structure as blade)
//     const article = document.createElement('article');
//     article.className = 'task-card';
//     article.dataset.priority = task.priority;
//     article.tabIndex = 0;
//     article.setAttribute('aria-labelledby', `task-${task.id}-title`);

//     article.innerHTML = `
//       <div class="task-left">
//         <span class="status-dot" aria-hidden="true"></span>
//         <div class="task-main">
//           <h3 id="task-${task.id}-title" class="task-title">
//             ${escapeHtml(task.title)}
//             <span class="badge badge-${task.priority}">${capitalize(task.priority)}</span>
//           </h3>
//           <p class="task-desc">${escapeHtml(task.desc || '')}</p>
//         </div>
//       </div>

//       <div class="task-right">
//         <div class="task-meta">
//           <div class="meta-item"><i class="fa-regular fa-calendar"></i><span class="meta-text">${escapeHtml(task.due || '')}</span></div>
//           <div class="meta-item"><i class="fa-regular fa-clock"></i><span class="meta-text">Created ${escapeHtml(task.created || '')}</span></div>
//         </div>

//         <div class="task-actions">
//           <button class="more-btn" aria-label="More options"><i class="fa-solid fa-ellipsis-vertical"></i></button>
//         </div>
//       </div>
//     `;

//     // Interactions: clicking more-btn toggles small menu (simple options)
//     const moreBtn = qs('.more-btn', article);
//     moreBtn.addEventListener('click', (e) => {
//       e.stopPropagation();
//       showTaskMenu(e.currentTarget, task);
//     });

//     // Mark done on double click (toggle)
//     article.addEventListener('dblclick', () => {
//       toggleTaskDone(task.id);
//     });

//     return article;
//   }

//   function renderTasks(tasks) {
//     const root = qs(tasksRootSelector);
//     if (!root) return;
//     // Clear only task cards and placeholder; keep panel wrapper
//     // We'll remove existing task-card nodes before the placeholder and keep other parts.
//     // Simpler: remove all children except the placeholder container (if exists).
//     const placeholder = qs(placeholderSelector, root);
//     root.innerHTML = ''; // clear
//     // Append each task card
//     tasks.forEach(t => {
//       root.appendChild(createTaskCard(t));
//     });
//     // Re-append placeholder
//     if (placeholder) {
//       root.appendChild(placeholder);
//     } else {
//       // recreate placeholder if not found (when first render)
//       const ph = createPlaceholder();
//       root.appendChild(ph);
//     }

//     updateSummaryCounts(tasks);
//     updateRightStats(tasks);
//     applyActiveFilter(); // ensure current filter applied
//   }

//   /* ---------- UI Utilities ---------- */
//   function createPlaceholder() {
//     const ph = document.createElement('div');
//     ph.className = 'add-new-placeholder';
//     ph.role = 'button';
//     ph.tabIndex = 0;
//     ph.setAttribute('aria-label', 'Add new task');
//     ph.innerHTML = `<i class="fa-solid fa-plus"></i><span>Add New Task</span>`;

//     ph.addEventListener('click', () => showAddTaskDialog());
//     ph.addEventListener('keydown', (e) => {
//       if (e.key === 'Enter' || e.key === ' ') {
//         e.preventDefault();
//         showAddTaskDialog();
//       }
//     });

//     return ph;
//   }

//   function showAddTaskDialog() {
//     // Simple prompt-based UI to keep things small and dependency-free.
//     // You can replace this with a modal in the future.
//     const title = prompt('Task title:', 'New Task');
//     if (!title) return;
//     const desc = prompt('Description (optional):', '');
//     const priority = prompt('Priority (low / medium / high):', 'low') || 'low';
//     const due = prompt('Due date (e.g. 2025-05-01):', '');
//     const created = new Date().toISOString().slice(0, 10);

//     const tasks = loadTasks();
//     const task = {
//       id: Date.now(),
//       title: title.trim(),
//       desc: desc ? desc.trim() : '',
//       priority: ['low', 'medium', 'high'].includes(priority.toLowerCase()) ? priority.toLowerCase() : 'low',
//       due: due.trim() || '',
//       created,
//       done: false
//     };
//     tasks.push(task);
//     saveTasks(tasks);
//     renderTasks(tasks);
//     flashMessage('Task added');
//   }

//   function showTaskMenu(buttonEl, task) {
//     // Remove any existing menu
//     const existing = qs('.tf-context-menu');
//     if (existing) existing.remove();

//     const menu = document.createElement('div');
//     menu.className = 'tf-context-menu';
//     menu.style.position = 'absolute';
//     menu.style.zIndex = 9999;
//     menu.style.minWidth = '140px';
//     menu.style.background = '#fff';
//     menu.style.boxShadow = '0 6px 18px rgba(0,0,0,0.08)';
//     menu.style.borderRadius = '10px';
//     menu.style.padding = '8px';
//     menu.style.fontSize = '14px';
//     menu.innerHTML = `
//       <div class="tf-menu-item" data-action="edit" style="padding:8px;cursor:pointer;border-radius:6px">Edit</div>
//       <div class="tf-menu-item" data-action="delete" style="padding:8px;cursor:pointer;border-radius:6px">Delete</div>
//       <div class="tf-menu-item" data-action="toggle" style="padding:8px;cursor:pointer;border-radius:6px">${task.done ? 'Mark Undone' : 'Mark Done'}</div>
//     `;

//     document.body.appendChild(menu);
//     // position
//     const rect = buttonEl.getBoundingClientRect();
//     menu.style.left = `${rect.left - 120}px`;
//     menu.style.top = `${rect.top + rect.height + 6}px`;

//     // menu actions
//     menu.addEventListener('click', (e) => {
//       const action = e.target.closest('.tf-menu-item')?.dataset?.action;
//       if (!action) return;
//       if (action === 'delete') {
//         if (confirm('Delete this task?')) {
//           deleteTask(task.id);
//         }
//       } else if (action === 'edit') {
//         editTaskPrompt(task.id);
//       } else if (action === 'toggle') {
//         toggleTaskDone(task.id);
//       }
//       menu.remove();
//     });

//     // remove on outside click
//     const onDocClick = (ev) => {
//       if (!menu.contains(ev.target) && ev.target !== buttonEl) {
//         menu.remove();
//         document.removeEventListener('click', onDocClick);
//       }
//     };
//     document.addEventListener('click', onDocClick);
//   }

//   function editTaskPrompt(taskId) {
//     const tasks = loadTasks();
//     const idx = tasks.findIndex(t => t.id === taskId);
//     if (idx < 0) return;
//     const t = tasks[idx];
//     const title = prompt('Edit title:', t.title);
//     if (!title) return;
//     const desc = prompt('Edit description:', t.desc || '');
//     const priority = prompt('Priority (low/medium/high):', t.priority);
//     const due = prompt('Due date (YYYY-MM-DD):', t.due || '');
//     t.title = title.trim();
//     t.desc = desc ? desc.trim() : '';
//     t.priority = ['low', 'medium', 'high'].includes((priority || '').toLowerCase()) ? priority.toLowerCase() : t.priority;
//     t.due = due.trim();
//     tasks[idx] = t;
//     saveTasks(tasks);
//     renderTasks(tasks);
//     flashMessage('Task updated');
//   }

//   function deleteTask(id) {
//     let tasks = loadTasks();
//     tasks = tasks.filter(t => t.id !== id);
//     saveTasks(tasks);
//     renderTasks(tasks);
//     flashMessage('Task deleted');
//   }

//   function toggleTaskDone(id) {
//     const tasks = loadTasks();
//     const idx = tasks.findIndex(t => t.id === id);
//     if (idx < 0) return;
//     tasks[idx].done = !tasks[idx].done;
//     saveTasks(tasks);
//     renderTasks(tasks);
//     flashMessage(tasks[idx].done ? 'Task marked done' : 'Task marked undone');
//   }

//   /* ---------- Filters ---------- */
//   let activeFilter = 'all'; // persisted only in-memory

//   function applyFilterToNode(taskNode, filter) {
//     if (!taskNode || !taskNode.dataset) return true;
//     if (filter === 'all') return true;
//     if (filter === 'today') {
//       const meta = qs('.meta-text', taskNode);
//       const due = meta ? meta.textContent : '';
//       return isDateToday(due);
//     }
//     if (filter === 'week') {
//       const meta = qs('.meta-text', taskNode);
//       const due = meta ? meta.textContent : '';
//       return isDateWithinDays(due, 7);
//     }
//     // priority filters
//     const priority = taskNode.dataset.priority;
//     if (['high', 'medium', 'low'].includes(filter)) {
//       return priority === filter;
//     }
//     return true;
//   }

//   function applyActiveFilter() {
//     const pills = qsa('.filter-pill');
//     pills.forEach(p => p.classList.toggle('active', p.dataset.filter === activeFilter));

//     const cards = qsa('.task-card');
//     cards.forEach(card => {
//       const show = applyFilterToNode(card, activeFilter);
//       card.style.display = show ? '' : 'none';
//     });
//   }

//   /* ---------- Summary + Stats ---------- */
//   function updateSummaryCounts(tasks) {
//     // compute counts
//     const total = tasks.length;
//     const low = tasks.filter(t => t.priority === 'low').length;
//     const med = tasks.filter(t => t.priority === 'medium').length;
//     const high = tasks.filter(t => t.priority === 'high').length;

//     const map = {
//       '.summary-cards .total-tasks .card-number': total,
//       '.summary-cards .low-priority .card-number': low,
//       '.summary-cards .medium-priority .card-number': med,
//       '.summary-cards .high-priority .card-number': high
//     };

//     Object.entries(map).forEach(([sel, val]) => {
//       const el = qs(sel);
//       if (el) el.textContent = String(val);
//     });
//   }

//   function updateRightStats(tasks) {
//     // right panel stats: total, completed, pending, completion rate, progress bar
//     const total = tasks.length;
//     const completed = tasks.filter(t => t.done).length;
//     const pending = total - completed;
//     const pct = total === 0 ? 0 : Math.round((completed / total) * 100);

//     const statMap = [
//       { sel: '.right-panel .stat-item:nth-child(1) .num', val: total },
//       { sel: '.right-panel .stat-item:nth-child(2) .num', val: completed },
//       { sel: '.right-panel .stat-item:nth-child(3) .num', val: pending },
//       { sel: '.right-panel .stat-item:nth-child(4) .num', val: `${pct}%` }
//     ];
//     statMap.forEach(s => {
//       const el = qs(s.sel);
//       if (el) el.textContent = s.val;
//     });

//     const bar = qs('.progress .bar');
//     if (bar) bar.style.width = `${pct}%`;
//   }

//   /* ---------- Small UI helpers ---------- */
//   function flashMessage(txt, ms = 1200) {
//     const id = 'tf-toast';
//     let el = qs(`#${id}`);
//     if (el) el.remove();
//     el = document.createElement('div');
//     el.id = id;
//     el.textContent = txt;
//     Object.assign(el.style, {
//       position: 'fixed',
//       right: '22px',
//       bottom: '22px',
//       background: 'linear-gradient(90deg,#6c47ff,#d72ce8)',
//       color: '#fff',
//       padding: '8px 12px',
//       borderRadius: '10px',
//       boxShadow: '0 6px 18px rgba(0,0,0,0.12)',
//       zIndex: 99999,
//       fontWeight: 600
//     });
//     document.body.appendChild(el);
//     setTimeout(() => el.remove(), ms);
//   }

//   /* ---------- Menu toggle for small screens ---------- */
//   function initMobileMenuToggle() {
//     const menuIcon = qs('#menu-icon');
//     if (!menuIcon) return;
//     menuIcon.addEventListener('click', () => {
//       const sidebar = qs('.sidebar');
//       if (!sidebar) return;
//       sidebar.style.display = sidebar.style.display === 'none' ? '' : 'none';
//     });
//   }

//   /* ---------- Filter pills init ---------- */
//   function initFilterPills() {
//     const pills = qsa('.filter-pill');
//     if (!pills.length) return;
//     pills.forEach(p => {
//       p.addEventListener('click', () => {
//         activeFilter = p.dataset.filter || 'all';
//         applyActiveFilter();
//       });
//     });
//   }

//   /* ---------- Utility functions ---------- */
//   function escapeHtml(str) {
//     if (!str) return '';
//     return String(str)
//       .replace(/&/g, '&amp;')
//       .replace(/</g, '&lt;')
//       .replace(/>/g, '&gt;')
//       .replace(/"/g, '&quot;')
//       .replace(/'/g, '&#039;');
//   }
//   function capitalize(s) { return s.charAt(0).toUpperCase() + s.slice(1); }

//   function isDateToday(d) {
//     if (!d) return false;
//     const dd = new Date(d);
//     if (isNaN(dd)) return false;
//     const today = new Date();
//     return dd.toDateString() === today.toDateString();
//   }
//   function isDateWithinDays(d, days) {
//     if (!d) return false;
//     const dd = new Date(d);
//     if (isNaN(dd)) return false;
//     const today = new Date();
//     const diff = (dd - today) / (1000 * 60 * 60 * 24);
//     return diff >= 0 && diff <= days;
//   }

//   /* ---------- Init / Boot ---------- */
//   function boot() {
//     // ensure placeholder exists inside tasks-list panel
//     const tasksPanel = qs('.panel.tasks-list') || qs('.tasks-list');

//     if (!tasksPanel) {
//       console.warn('TaskFlow: tasks container (.panel.tasks-list or .tasks-list) not found.');
//       return;
//     }

//     // If tasks-list is the inner container, we place placeholder inside it
//     let root = qs('.tasks-list');
//     if (!root) {
//       // if .panel.tasks-list exists but not .tasks-list, use panel itself
//       root = tasksPanel;
//       root.classList.add('tasks-list');
//     }

//     // add placeholder if missing
//     if (!qs(placeholderSelector, root)) {
//       root.appendChild(createPlaceholder());
//     }

//     // load tasks and render
//     const tasks = loadTasks();
//     renderTasks(tasks);

//     // init UI interactions
//     initMobileMenuToggle();
//     initFilterPills();

//     // wire add button in header (optional)
//     const headerAdd = qs('.btn-add');
//     if (headerAdd) {
//       headerAdd.addEventListener('click', () => showAddTaskDialog());
//     }

//     // click outside to close any open context menus
//     document.addEventListener('click', () => {
//       const menu = qs('.tf-context-menu');
//       if (menu) menu.remove();
//     });

//     // keyboard: press "/" to focus search (not included) or "n" to create new task
//     document.addEventListener('keydown', (e) => {
//       if (e.key === 'n' && !e.metaKey && !e.ctrlKey && !e.altKey) {
//         // quick add
//         showAddTaskDialog();
//       }
//     });
//   }

//   // Boot on DOM ready
//   if (document.readyState === 'loading') {
//     document.addEventListener('DOMContentLoaded', boot);
//   } else {
//     boot();
//   }
// })();
