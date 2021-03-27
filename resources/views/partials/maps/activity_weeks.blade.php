<div class="col-md-4 col-sm-4 mb">
    <!-- REVENUE PANEL -->
    <div class="green-panel pn">
        <div class="green-header">
            <h5>WEEKLY MAP POPULARITY</h5>
        </div>
        <div class="chart mt">
            <div class="sparkline" data-type="line" data-resize="true" data-height="75" data-width="90%"
                 data-line-width="1" data-line-color="#fff" data-spot-color="#fff" data-fill-color=""
                 data-highlight-line-color="#fff" data-spot-radius="4"
                 data-data="[{{ implode(',', $map->weeklyActivity()) }}]"></div>
        </div>
        <p class="mt">matches played per week<br />
        (last 12 weeks)</p>
    </div>
</div>