<?php
/**
 * Created by PhpStorm.
 * User: flytienon
 * Date: 2018/12/26
 * Time: 15:44
 */
?>
@if (count($feed_items))
    <ol class="statuses">
        @foreach ($feed_items as $status)
            @include('statuses._status', ['user' => $status->user])
        @endforeach
        <!--分页-->
        {!! $feed_items->render() !!}
    </ol>
@endif
