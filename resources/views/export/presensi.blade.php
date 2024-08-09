<table id="datatables" class="table table-bordered" >
    <thead>
    <tr>
        <th rowspan="2">Nama Heroes</th>
        <th rowspan="2">Status</th>
        <th colspan="{{count($dates)}}" style="text-align: center">Tanggal</th>
    </tr>
    <tr>
        @for($i = 0; $i < count($dates); $i++)
            <th>{{$dates[$i]['day']}}</th>
        @endfor
            <th>Hadir</th>
            <th>Lupa Kartu</th>
            <th>Perjadin</th>
            <th>Sakit</th>
            <th>Tidak Hadir</th>
            <th>WFH</th>
            <th>Cuti</th>
    </tr>
    </thead>
    <tbody>
    @foreach(@$employees as $employee)
        <tr>
            <td>{{$employee->name}}</td>
            <td>{{$employee->status->status}}</td>
            @php
                $present = 0;
                $lk = 0;
                $pj = 0;
                $s = 0;
                $t = 0;
                $wfh = 0;
                $cuti = 0;
            @endphp
            @for($i = 0; $i < count($dates); $i++)
                <td data-id="{{$employee->id}}" style="vertical-align: middle">
                    @php
                        $check = collect($dates[$i]['presensi'])->where('employee_id', $employee->id);
                    @endphp
                    @if(count($check) > 0)
                        @if(@$check->first()['type'] == "presensi")
                            @php @$present++; @endphp
                            <p>H</p>
                        @elseif(@$check->first()['type'] == "lupa-kartu")
                            @php @$lk++; @endphp
                            <p>LK</p>
                        @elseif(@$check->first()['type'] == "perjadin")
                            @php @$pj++; @endphp
                            <p>PJ</p>
                        @elseif(@$check->first()['type'] == "sakit")
                            @php @$s++; @endphp
                            <p>S</p>
                        @elseif(@$check->first()['type'] == "wfh")
                            @php @$wfh++; @endphp
                            <p>WFH</p>
                        @elseif(@$check->first()['type'] == "wfh")
                            @php @$cuti++; @endphp
                            <p>Cuti</p>
                        @endif
                    @else
                        @if($dates[$i]['absent'])
                            @php @$t++; @endphp
                            <p>T</p>
                        @endif
                    @endif
                </td>
            @endfor

                            <td>{{$present}}</td>
                            <td>{{$lk}}</td>
                            <td>{{$pj}}</td>
                            <td>{{$s}}</td>
                            <td>{{$t}}</td>
                            <td>{{$wfh}}</td>
                            <td>{{$cuti}}</td>
        </tr>
    @endforeach
    <tr>

        <td colspan="{{count($dates) + 2}}" style="text-align: center">Dari {{@$start}} - {{@$end}}</td>
    </tr>
    </tbody>
</table>
