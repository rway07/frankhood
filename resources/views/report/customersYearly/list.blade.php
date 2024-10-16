<?php $count = 1; ?>
<table class="table-condensed small_table" id="customers_table">
    <thead>
        <tr>
            <h4> LISTA SOCI PER L'ANNO {{ $years }}</h4>
            <h5> Totale membri: {{ $counter }}</h5>
        </tr>
        <tr class="center">
            <th> Nr.</th>
            <th> Socio</th>
            <th> Alias</th>
            <th> Data nascita</th>
            <th></th>
            <th> Telefono</th>
        </tr>
    </thead>
    <tbody>
    @foreach($data as $i => $n)
        <!-- customer row -->
        <tr class="top_border small_table_tr">
            <td class="upper_element">
                @if($extended == false)
                    @if($n['main']->late == 'True')
                        {{ $count }} - {{ $count }}
                    @else
                        {{ $count }} - {{ $count + count($n['group']) -1 }}
                    @endif
                @else
                    {{ $i + 1 }}
                @endif
            </td>
            <td class="upper_element">
                @if($n['main']->late == 'True')
                    [MOROSO]
                @endif
                <b>{{ $n['main']->last_name . ' ' . $n['main']->first_name }}</b>
            </td>
            <td class="upper_element">
                {{ $n['main']->alias }}
            </td>
            <td class="upper_element">
                {{ strftime("%d/%m/%Y", strtotime($n['main']->birth_date)) }}
            </td>
            <td class="upper_element space">
            </td>
            <td class="upper_element center">
                @if($n['main']->phone != "")
                    {{ $n['main']->phone }}
                    @if($n['main']->mobile_phone != "")
                            -  {{ $n['main']->phone }}
                    @endif
                @elseif($n['main']->mobile_phone != "")
                    {{ $n['main']->mobile_phone }}
                @else
                    ---
                @endif
            </td>
        </tr>
        <!-- end customer row-->
        <!-- group row -->
        <tr class="bottom_border">
            <?php $numGroup = count($n['group']); ?>
            <td colspan="5" class="group_cell">
                <table>
                    @foreach($n['group'] as $key => $g)
                        @if(isset($g))
                            @if($key == 0)
                                <tr>
                                    <td>
                                        <b>GRUPPO:&nbsp;</b>
                                    </td>
                                    <td>
                                        {{ $g->name }}
                                    </td>
                                    <td>
                                        ({{ $g->alias }})
                                    </td>
                                    <td>
                                        {{ strftime("%d/%m/%Y", strtotime($g->birth_date)) }}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td></td>
                                    <td>
                                        {{ $g->name }}
                                    </td>
                                    <td>
                                        ({{ $g->alias }})
                                    </td>
                                    <td>
                                        {{ strftime("%d/%m/%Y", strtotime($g->birth_date)) }}
                                    </td>
                                </tr>
                            @endif
                        @endif
                    @endforeach
                </table>
            </td>
        </tr>
        <!-- end group row-->
        <?php
        if ($n['main']->late == 'True') {
            $count += 1;
        } else {
            $count += count($n['group']);
        }
        ?>
    @endforeach
    </tbody>
</table>
