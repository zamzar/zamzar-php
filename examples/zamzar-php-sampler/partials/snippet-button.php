    <?php if($_SERVER['REQUEST_URI'] !== '/index.php' && $_SERVER['REQUEST_URI'] !== '/') { ?>
        <button class="button is-normal is-outlined is-pulled-right" onclick="document.getElementById('snippet').classList.toggle('is-active');">
            <span class="icon is-small">
                <i class="fas fa-code"></i>
            </span>
        </button>
    <?php } ?>
    
