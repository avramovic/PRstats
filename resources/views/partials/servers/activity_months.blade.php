<div class="col-md-4 col-sm-4 mb">
    <!-- REVENUE PANEL -->
    <div class="green-panel pn">
        <div class="green-header">
            <h5>YEARLY SERVER ACTIVITY</h5>
        </div>
        <div class="chart mt">
            <div class="sparkline" data-type="line" data-resize="true" data-height="75" data-width="90%"
                 data-line-width="1" data-line-color="#fff" data-spot-color="#fff" data-fill-color=""
                 data-highlight-line-color="#fff" data-spot-radius="4"
                 data-data="[{{ implode(',', $server->monthlyActivity()) }}]"></div>
        </div>
        <p class="mt">unique players per month<br />
        (last 12 months)</p>
    </div>
</div>