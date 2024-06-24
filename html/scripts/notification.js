let notificationElement = document.getElementById("notification");

window.NotificationType = {
    SUCCESS: {name: "success", clazz: "mdi-check"},
    WARNING: {name: "warning", clazz: "mdi-alert"},
    ERROR: {name: "error", clazz: "mdi-close"}
}
window.notify = (type, title, renderDirectly) => {
    if(!type || !title)
        throw new Error("Missing type or title");
    const url = new URL(location);
    url.searchParams.set("notification-type", type);
    url.searchParams.set("notification-message", encodeURIComponent(title));
    if(renderDirectly)
        return render(type, title);
    history.pushState({}, "", url);
}

function handleNotificationChange() {
    let params = window.location.search;
    if(params) {
        params = new URLSearchParams(params);
        const notificationType = params.get("notification-type");
        const notificationMessage = decodeURIComponent(params.get("notification-message"));
        render(notificationType, notificationMessage);
    }
}

function render(type, notificationMessage) {
    type = window.NotificationType[type];
    if(!type)
        return;
    const notificationElement = document.querySelector(".notification");
    if(notificationElement) return;
    document.body.insertAdjacentHTML("afterbegin", `
        <div class="notification ${type.name}" role="dialog">
            <span class="mdi ${type.clazz}"></span>
            <h3 title="${notificationMessage}">${notificationMessage}</h3>
        </div>
    `);

    setTimeout(() => document.querySelector(".notification").remove(), 5e3);
    window.history.pushState({}, "", window.location.href.split("?")[0]);
}

window.addEventListener("DOMContentLoaded", () => {
    handleNotificationChange();
});