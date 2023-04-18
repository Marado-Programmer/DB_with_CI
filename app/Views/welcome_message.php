<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cat facts</title>
    <meta name="description" content="Facts about cats">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <style {csp-style-nonce}>
    </style>
    <script async>
        const facts = () => fetch("/facts", {
            credentials: "include"
        }).then(res => res.json());

        const facts_promise = facts();

        window.addEventListener("DOMContentLoaded", () => {
            const facts_list = document.getElementById("facts");
            const more = document.getElementById("more");
            facts_promise.then(facts => manage(facts, facts_list, more));

            more.onclick = () => facts().then(facts => manage(facts, facts_list, more));
        });

        function manage(facts, list, more) {
            more.setAttribute("disabled", true);
            for (const i in facts) {
                const li = document.createElement("li");
                li.append(document.createTextNode(facts[i].fact));
                list.append(li);
            }
            more.removeAttribute("disabled");
        }
    </script>
</head>

<body>

    <h1>Some facts about cats</h1>
    <ul id="facts"></ul>
    <button id="more" type="button">More!</button>

</body>

</html>
