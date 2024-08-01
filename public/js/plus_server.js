$("#add-server-btn").click(function () {
let count = $(".nav-tabs .server-btns").length + 1;

$("#server-btns-container").append(`
<button class="server-btns nav-link rounded-0 text-nowrap" id="server-${count}-tab" data-bs-toggle="tab"
    data-bs-target="#server-${count}" type="button" role="tab" aria-controls="server-${count}"
    aria-selected="true">Server ${count} <span class="delete-server-btn">&times;</span></button>
`);

let newtab = `<div class="tab-pane fade" id="server-${count}" role="tabpanel" aria-labelledby="server-${count}-tab"
    tabindex="0">

    <div class="row">
        <div class="">
            <input id="server_name" type="text" class="" name="server_name[]" value=""
                autocomplete="server_name" placeholder="name">
        </div>
    </div>
    <div class="row">
        <div class="">
            <input id="server_url" type="url" class="" name="server_url[]" value=""
                autocomplete="server_url" placeholder="URL">
        </div>
    </div>

    <div class="row">
        <div class="">
            <input id="server_referer" type="url" class="" name="server_referer[]" placeholder="REFERER"
                value="" autocomplete="server_referer">
        </div>
    </div>
    <div class="">
        <select id="server_type" name="server_type[]" class="" aria-label="Default select example"
            autocomplete="server_type">
            <optgroup class="ms-3 collapse show" id="collapseExample">
                <option value="Direct Player" selected>
                    Direct Player
                </option>
                <option value="Embed Player">
                    Embed Player
                </option>
            </optgroup>
        </select>
    </div>
</div>`;

$(".tab-content").append(newtab);

$(`#server-${count}-tab`).click();

$(".delete-server-btn").click(function(event) {
const tabId = $(event.target).parent().attr("aria-controls");
$(event.target).parent().remove();
$(`#${tabId}`).remove();
// Click the tab with the previous index
$(".server-btns").eq(count - 1).click();
});
});
