const topic = window.location.pathname.split("/").filter(Boolean)[0];
const subtopicItems = document.querySelectorAll(".subtopic-items");

subtopicItems.forEach(subtopic => {
    if(subtopic.getAttribute("data-url") != `/${topic}`) {
        subtopic.classList.add("hidden");
    }
});