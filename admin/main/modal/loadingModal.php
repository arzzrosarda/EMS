<!-- CSS FOR MODAL BOXES -->
<style type="text/css">
    div#pageblocker {
        position:fixed;
        top:0;
        left:0;
        z-index:1000;

        width:100%;
        height:100%;

        background:#666;
        opacity:.3;

        display:none;
    }
    table#modalbox_container {
        position:fixed;
        top:0;
        left:0;
        z-index:2000;

        width:100%;
        height:100%;

        display:none;
    }
    .tail-chase {
        --uib-size: 100px;
        --uib-color: white;
        --uib-speed: 1.5s;
        --dot-size: calc(var(--uib-size) * 0.17);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        height: var(--uib-size);
        width: var(--uib-size);
        animation: smoothRotate calc(var(--uib-speed) * 1.8) linear infinite;
    }

    .dot {
        position: absolute;
        top: 0;
        left: 0;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        height: 100%;
        width: 100%;
        animation: rotate var(--uib-speed) ease-in-out infinite;
    }

    .dot::before {
        content: '';
        height: var(--dot-size);
        width: var(--dot-size);
        border-radius: 50%;
        background-color: var(--uib-color);
        transition: background-color 0.3s ease;
    }

    .dot:nth-child(2),
    .dot:nth-child(2)::before {
        animation-delay: calc(var(--uib-speed) * -0.835 * 0.5);
    }

    .dot:nth-child(3),
    .dot:nth-child(3)::before {
        animation-delay: calc(var(--uib-speed) * -0.668 * 0.5);
    }

    .dot:nth-child(4),
    .dot:nth-child(4)::before {
        animation-delay: calc(var(--uib-speed) * -0.501 * 0.5);
    }

    .dot:nth-child(5),
    .dot:nth-child(5)::before {
        animation-delay: calc(var(--uib-speed) * -0.334 * 0.5);
    }

    .dot:nth-child(6),
    .dot:nth-child(6)::before {
        animation-delay: calc(var(--uib-speed) * -0.167 * 0.5);
    }

    @keyframes rotate {
        0% {
            transform: rotate(0deg);
        }
        65%,
        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes smoothRotate {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>

<script>
    //call this function to hide modal boxes
    function hideModal() {
        $("div.modal_display").fadeOut();
        $("table#modalbox_container").fadeOut();
        $("div#pageblocker").fadeOut();
    }
    //call this function before showing modal boxes
    function showModal() {
        $("#pageblocker").show();
        $("#modalbox_container").show();
        //$("#modalbox_container #loader").fadeIn();
    }
</script>

<!-- PAGE BLOCKER FOR MODAL BOXES -->
<div id="pageblocker"></div>

<!-- CONTAINER FOR MODAL BOXES -->
<table id="modalbox_container" align="center">
    <tr>
        <td valign="middle" align="center">
            <div id="loader" style="display: none;">
                <div class="tail-chase">
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>
            </div>
        </td>
    </tr>
</table>