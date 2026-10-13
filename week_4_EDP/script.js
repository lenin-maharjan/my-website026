
const inputField = document.getElementById("inputField");
const submitBtn = document.getElementById("submitBtn");
const output = document.getElementById("output");
const errorMessage = document.getElementById("errorMessage");
const addBtn = document.getElementById("addBtn");
const buttonContainer = document.getElementById("buttonContainer");
const delegationOutput = document.getElementById("delegationOutput");
const protectedText = document.getElementById("protectedText");
const copyStatus = document.getElementById("copyStatus");

const observers = [
    document.getElementById("observerOne"),
    document.getElementById("observerTwo")
];
let buttonNumber = 0;

function updateObservers(value) {
    const message = value.trim() || "Waiting for input...";

    observers.forEach(function (observer, index) {
        observer.textContent = `Observer ${index + 1}: ${message}`;
    });
}

inputField.addEventListener("input", function () {
    updateObservers(inputField.value);
});

submitBtn.addEventListener("click", function () {
    const input = inputField.value.trim();

    if (!input) {
        output.textContent = "";
        errorMessage.textContent = "Please enter a message before submitting.";
        inputField.focus();
        return;
    }

    errorMessage.textContent = "";
    output.textContent = "You entered: " + input;
});

addBtn.addEventListener("click", function () {
    buttonNumber += 1;

    const newButton = document.createElement("button");
    newButton.type = "button";
    newButton.className = "dynamic-button";
    newButton.dataset.buttonNumber = buttonNumber;
    newButton.textContent = `Dynamic Button ${buttonNumber}`;
    buttonContainer.appendChild(newButton);
});

buttonContainer.addEventListener("click", function (event) {
    const clickedButton = event.target.closest(".dynamic-button");

    if (!clickedButton || !buttonContainer.contains(clickedButton)) {
        return;
    }

    delegationOutput.textContent =
        `${clickedButton.textContent} was clicked using event delegation.`;
});

protectedText.addEventListener("selectstart", function (event) {
    event.preventDefault();
    copyStatus.textContent = "Text selection is disabled for this protected text.";
});

protectedText.addEventListener("copy", function (event) {
    event.preventDefault();
    copyStatus.textContent = "Copying is disabled for this protected text.";
});
