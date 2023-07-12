window.setInterval(async() => {
    const response = await fetch('/ping')
    if (response.status === 403) {
        window.location.reload();
    }
}, 60000);