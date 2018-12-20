const comments = document.getElementById('comments');

if(comments) {
    comments.addEventListener('click', (e) => {
        if (e.target.className === 'btn btn-danger delete-comment') {
            if(confirm('Are you sure?')) {
                const id = e.target.getAttribute('data-id');

                fetch(`/comment/delete/${id}`, {
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        }
    })
}