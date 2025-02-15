<script>
    function createBubble() {
        const bubble = document.createElement('div');
        bubble.classList.add('bubble');
        document.getElementsByClassName('container')[0].appendChild(bubble);

        let size = Math.random() * 100 + 50;
        bubble.style.width = `${size}px`;
        bubble.style.height = `${size}px`;

        let leftPosition = Math.random() * window.innerWidth;
        bubble.style.left = `${leftPosition}px`;

        bubble.style.animationDuration = 21 + 's';

        setTimeout(() => {
            bubble.remove();
        }, 21000);
    }

    setInterval(createBubble, 1500);
</script>
