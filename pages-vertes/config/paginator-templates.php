<?php
/**
 * Created by PhpStorm.
 * User: berni
 * Date: 2018-03-14
 * Time: 09:40
 */
return [
    'number' => '<a href="{{url}}" class="paginator"> {{text}} </a>',
    'current' => ' {{text}} ',
    'prevActive' => '<a href="{{url}}" class="paginator"  title="'.__('Previous').'"> {{text}} </a>',
    'prevDisabled' => '<a href="{{url}}" class="paginator" style="display: none;"> {{text}} </a>',
    'nextActive' => '<a href="{{url}}" class="paginator" title="'.__('Next').'"> {{text}} </a>',
    'nextDisabled' => '<a href="{{url}}" class="paginator" style="display: none;"> {{text}} </a>',
    'first' => '<a href="{{url}}" class="paginator" title="'.__('First page').'"> {{text}} </a>',
    'last' => '<a href="{{url}}" class="paginator" title="'.__('Last page').'"> {{text}} </a>',
];
