<div class="mt-5 rounded-top-4 bg-secondary-subtle">
    <div class="p-3 d-flex justify-content-center align-items-center gap-2 flex-wrap">
        <p class="mb-0">© 2026 Copyright:</p>

        <a id="footer"
           class="text-body text-decoration-none d-inline-flex align-items-center gap-2"
           href="#"></a>
    </div>
</div>

<script>
    const footer = document.getElementById('footer');

    const username = "Nulsy-0";

    fetch(`https://api.github.com/users/${username}`)
        .then(res => res.json())
        .then(user => {
            footer.href = user.html_url;
            footer.target = "_blank";

            footer.innerHTML = `
                <img
                    src="${user.avatar_url}"
                    alt="${username}"
                    class="rounded-circle border"
                    width="25"
                    height="25"
                >
                <span class="fw-semibold">${username}</span>
            `;
        });
</script>