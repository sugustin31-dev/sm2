document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('comment-form');
  const feed = document.getElementById('comments-feed');

  function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
  }

  async function loadComments() {
    try {
      const res = await fetch('/api/comments.php');
      if (!res.ok) return;
      const comments = await res.json();
      feed.innerHTML = '';
      comments.forEach(c => {
        const div = document.createElement('div');
        div.className = 'comment';
        div.innerHTML = `
          <strong>${escapeHtml(c.name)}</strong>
          <p>${escapeHtml(c.message)}</p>
          <small>${escapeHtml(c.created_at)}</small>
        `;
        feed.appendChild(div);
      });
    } catch (_) {}
  }

  form.addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData(form);
    try {
      const res = await fetch('/api/comment.php', { method: 'POST', body: fd });
      const data = await res.json();
      if (data.success) {
        form.reset();
        await loadComments();
      } else {
        alert(data.error);
      }
    } catch (_) {
      alert('Error al enviar el comentario');
    }
  });

  loadComments();
});
