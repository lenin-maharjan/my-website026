let countdownTimer;

function getMessage() {
	const result = document.getElementById("result");
	clearInterval(countdownTimer);
	result.textContent = "Loading...";

	fetch("api.php", {
		method: "GET"
	})
		.then(response => {
			if (!response.ok) {
				throw new Error("The API request failed.");
			}

			return response.text();
		})
		.then(data => {
			let secondsRemaining = 5;
			result.textContent = `${data} Disappearing in ${secondsRemaining} seconds.`;

			countdownTimer = setInterval(() => {
				secondsRemaining -= 1;

				if (secondsRemaining === 0) {
					clearInterval(countdownTimer);
					result.textContent = "";
					return;
				}

				result.textContent = `${data} Disappearing in ${secondsRemaining} seconds.`;
			}, 1000);
		})
		.catch(error => {
			result.textContent = error.message;
		});
}
