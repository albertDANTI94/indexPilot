

document.addEventListener('DOMContentLoaded', () => {

    const buttons = document.querySelectorAll('.tab-btn');
    const contents = document.querySelectorAll('.tab-content');

    buttons.forEach(button => {

        button.addEventListener('click', () => {

            buttons.forEach(btn =>
                btn.classList.remove('active')
            );

            contents.forEach(content =>
                content.classList.remove('active')
            );

            button.classList.add('active');

            const tab = button.dataset.tab;

            document
                .getElementById(tab)
                .classList.add('active');

        });

    });

});

