<div id="checkout-page">
    <h1 id="title"><?= $title ?></h1>
    <form id="page-content" action="<?= base_url("/cart/placeOrder") ?>">
        <?= csrf_field() ?>
        <div id="left" class="hover-box">
            <h2>Delivery Options</h2>
            <input type="radio" id="delivery_option1" name="delivery_option" value="delivery" onchange="hidePickup()" checked></input>
            <label for="delivery_option1">Delivery</label>
            <input type="radio" id="delivery_option2" name="delivery_option" value="pickup" onchange="showPickup()"></input>
            <label for="delivery_option2">Pick Up</label>
            <div id="pickup-location-container">
                <label for="pickup-location"></label>
                <select id="pickup-location" name="pickup_location" type="select" class="form-select" >
                    <?php foreach($pickup_locations as $location){?>
                        <option value="<?=$location["id"]?>">
                            <?= $location["name"] ?>
                        </option>
                    <?php }?>

                </select>
            </div>

            <h2>Shipping/Billing Address</h2>
            <input required name="first_name" class="form-control" placeholder="First Name" />
            <input required name="last_name" class="form-control" placeholder="Last Name" />
            <input required name="street" class="form-control" placeholder="Street" />
            <input required name="house_number" class="form-control" type="number" placeholder="House Number" />
            <input required name="postal_code" class="form-control" placeholder="ZIP or Postal Code" />
            <input required name="city" class="form-control" placeholder="City" />
            <input required name="country" class="form-control" placeholder="Country" />
            <h2>Date Of Delivery</h2>
            <?php
            // minimum: the next day
            $date = new DateTime();
            $date->modify('+1 day'); ?>
            <input required type="date" name="delivery_date" class="form-control" value="<?= $date->format('Y-m-d'); ?>" min="<?= $date->format('Y-m-d'); ?>" />
        </div>
        <div id="right" class="hover-box">
            <h2>Overview</h2>
            <div class="overview-row">
                <p>Total:</p>
                <p id="total">€<?= number_format($totalPrice, 2) ?></p>
            </div>
            <hr />
            <button class="btn btn-primary" type="submit">Place Order</button>
        </div>
    </form>
</div>
<script>
    let pickup_location_container = document.getElementById("pickup-location-container");
    let pickup_location = document.getElementById("pickup-location");

    // hide by default
    hidePickup();

    function showPickup() {
        console.log("adding active")
        pickup_location_container.classList.remove("inactive");
        pickup_location.setAttribute("required", "");
    }

    function hidePickup() {
        console.log("removing active")
        pickup_location_container.classList.add("inactive");
        pickup_location.removeAttribute("required");
    }
</script>