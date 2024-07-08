window.onload = function () {
    var step = document.getElementById("steps");
    Sortable.create(steps, {
        animation: 150,
        handle: ".handle",
        onEnd: function (evt) {
            var items = steps.querySelectorAll(".step");
            items.forEach(function (item, index) {
                item.querySelector(".step-number").innerHTML =
                    "手順" + (index + 1);
            });
        },
    });

    steps.addEventListener("click", function (evt) {
        if (
            evt.target.classList.contains("step-delete") ||
            evt.target.closest(".step-delete")
        ) {
            evt.target.closest(".step").remove();
            var items = steps.querySelectorAll(".step");
            items.forEach(function (item, index) {
                item.querySelector(".step-number").innerHTML =
                    "手順" + (index + 1);
            });
        }
    });
    var addStep = document.getElementById("step-add");
    addStep.addEventListener("click", function () {
        var stepCount = steps.querySelectorAll(".step").length;
        var step = document.createElement("div");
        step.classList.add("step");
        step.classList.add("flex");
        step.classList.add("items-center");
        step.classList.add("mb-4");
        step.classList.add("justify-between");

        step.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="handle w-10 h-10 text-gray-600">
    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
  </svg>
  
        <p class ='step-number w-16'>手順${stepCount + 1}</p>
            <input type="text" name="steps[]" placeholder="手順を入力" class="border border-gray-300 p-2 mb-4 w-full rounded">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 step-delete text-gray-600 ml-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
              </svg>

       
        `;

        steps.appendChild(step);
    });

    //材料
    var ingredients = document.getElementById("ingredients");
    Sortable.create(ingredients, {
        animation: 150,
        handle: ".handle",
        onEnd: function (evt) {
            var items = ingredients.querySelectorAll(".ingredient");
            items.forEach(function (item, index) {
                item.querySelector(
                    ".ingredient-name"
                ).name = `ingredients[${index}][name]`;
                item.querySelector(
                    ".ingredient-quantity"
                ).name = `ingredients[${index}][quantity]`;
            });
        },
    });
    ingredients.addEventListener("click", function (evt) {
        if (
            evt.target.classList.contains("ingredient-delete") ||
            evt.target.closest(".ingredient-delete")
        ) {
            evt.target.closest(".ingredient").remove();
            var items = ingredients.querySelectorAll(".ingredient");
            items.forEach(function (item, index) {
                item.querySelector(
                    ".ingredient-name"
                ).name = `ingredients[${index}][name]`;

                item.querySelector(
                    ".ingredient-quantity"
                ).name = `ingredients[${index}][quantity]`;
            });
        }
    });
};
