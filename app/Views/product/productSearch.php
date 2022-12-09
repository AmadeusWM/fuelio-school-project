<div id="search-page">
    <form name="filter-form" id="filter-container" class="hover-box" action="<?= base_url("/Store/ProductSearchController/search") ?>">
        <div class="input-wrapper">
            <!-- <label for="search_terms" class="form-label">Search Terms</label> -->
            <input type="text" id="search_terms" name="search_terms" class="form-control" value="<?= isset($filter["search_terms"]) ? $filter["search_terms"] : "" ?>" placeholder="Search Terms">
        </div>
        <div class="input-wrapper">

            <!-- <label for="origin" class="form-label">Origin</label> -->
            <input type="text" id="origin" name="origin" class="form-control" value="<?= isset($filter["origin"]) ? $filter["origin"] : "" ?>" placeholder="Origin">
        </div>
        <div class="input-wrapper">
            <!-- <label for="category" class="form-label">Category</label> -->
            <select id="category" name="category" class="form-select">
                <?php
                array_unshift($product_categories, ["name" => "All"]);
                foreach ($product_categories as $product_category) { ?>
                    <option <?= isset($filter["category"])
                                && $filter["category"] == $product_category["name"]
                                ? "selected" : "" ?>>
                        <?= $product_category["name"] ?>
                    </option>
                <?php } ?>
            </select>

        </div>
        <div class="input-wrapper">
            <!-- <label for="max_price" class="form-label">Max Price</label> -->
            <div class="max-price-input-wrapper">
                <input type="range" id="max-price-range" step="5" min="0" max="9999" value="<?= isset($filter["max_price"]) ? $filter["max_price"] : "0" ?>" class="form-range" onchange="updateMaxPrice(this.value, 'max-price-input')">
                <input type="number" id="max-price-input" step="5" min="0" name="max_price" class="form-control" value="<?= isset($filter["max_price"]) ? $filter["max_price"] : "0" ?>" onchange="updateMaxPrice(this.value, 'max-price-range')" />
            </div>

        </div>
        <button id="filter-button" class="btn btn-primary">Filter</button>
    </form>
    <div id="products-list-container">
        <?php echo $products_list ?>
    </div>
</div>
<script>
    function updateMaxPrice(value, id) {
        let maxPriceSpan = document.getElementById(id);
        maxPriceSpan.value = value;
    }
</script>