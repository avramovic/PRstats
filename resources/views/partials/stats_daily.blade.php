<div class="col-md-3 col-sm-3 mb">
    @php
        $word = !isset($field) || ($field == 'created_at') ? 'new' : 'active';
    @endphp
    <div class="green-panel pn">
        <div class="green-header">
            <h5>{!! $slot ?? ('DAILY '.strtoupper($word) . ' ' . strtoupper($table)) !!}</h5>
        </div>
        <div class="chart mt">
            <div class="sparkline" data-type="line" data-resize="true" data-height="75" data-width="90%"
                 data-line-width="1" data-line-color="#fff" data-spot-color="#fff" data-fill-color=""
                 data-highlight-line-color="#fff" data-spot-radius="4"
                 data-data="[{{ implode(',', \PRStats\Helpers\Statistics::dailyTrends($table)) }}]"></div>
        </div>
        <p class="mt">{!! $subtitle ?? ($word . ' ' . $table . ' per day<br />(last 7 days)') !!}</p>

    </div>
</div>