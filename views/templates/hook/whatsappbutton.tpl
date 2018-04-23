<!-- Block whatsappButton -->
<div id="whatsappbutton_block_home" class="block">
    <div class="block_content">
        {if isset($whatsapp_phone_number) && $whatsapp_phone_number}
                <a class="whatsappchat-anchor" target="_blank" href="https://web.whatsapp.com/send?l=es&amp;phone={$whatsapp_phone_number}">
                    <div class="whatsapp center">
                        <span style="background-color: #25d366">
                            <i class="icon-whatsapp icon-lg"></i> You can complete your order on Whatsapp!
                        </span>
                    </div>
                </a>
            {else}
                World
            {/if}
        </p>

    </div>
</div>
<!-- /Block whatsappButton-->