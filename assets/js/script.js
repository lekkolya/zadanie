const topic = window.location.pathname.split("/").filter(Boolean);
const subtopicItems = document.querySelectorAll(".subtopic-items");

const activeTopic = topic.length > 0 ? topic[0] : "topic-1";

subtopicItems.forEach(subtopic => {
    if (subtopic.getAttribute("data-url") !== `/${activeTopic}`) {
        subtopic.classList.add("hidden");
    } else {
        subtopic.classList.remove("hidden");
    }
});