<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('copy-to-clipboard', (event) => {
            // console.log(event.value);
            navigator.clipboard.writeText(event.value);
        });
    });
</script>
