@extends('layouts.frontend')
@section('title', "Halaman Utama")

@section('css')
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
@endsection

@section('content')
<div class="container">
    @php
        $schedule = $schedules[int_to_month($month)];
        $todayDay = date("d");
        $todayMonth = date("n");
        $todayYear = date("Y");
        $dayShort = ["Mgu", "Sen", "Sel", "Rab", "Kam", "Jmt", "Sbt"];
    @endphp
    @if (Request::get('all') == 1)
    <div class="all-calendar">
        <h2>Jadwal Tahunan</h2>
    <table>

        <thead>
            <td>Bulan/Hari</td>
            @for ($i = 0; $i <= 5; $i++)
                @foreach ($dayShort as $dn)
                    <td>{{ $dn }}</td>
                @endforeach
            @endfor
        </thead>
        <tbody>
            @foreach ($schedules as $month => $schedule)
                <tr>
                    <td>{{ $month }}</td>
                    @foreach ($schedule as $day => $detail)
                        @if($day == 1)
                            @for ($i = 0; $i<=day_to_num($detail["day_name"])-1; $i++)
                            <td></td>
                            @endfor
                        @endif
                        <td id="d-all-{{ $day }}">{{ $day }}</td>
                        @php
                        $shift =
                        "Group A ".translate_pattern($detail["shift"]["A"])."<hr>".
                            "Group B ".translate_pattern($detail["shift"]["B"])."<hr>".
                            "Group C ".translate_pattern($detail["shift"]["C"])."<hr>".
                            "Group D ".translate_pattern($detail["shift"]["D"]);
                        @endphp
                        <script>
                            tippy('#d-all-{{ $day }}', {
                            content: '{!! $shift !!}',
                            allowHTML: true,
                            trigger: 'click',
                            });
                        </script>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    @else
    <div class="calendar">
        <form action="" method="GET">
            <div class="calendar__opts">
                <select name="month" id="calendar__month">
                    @for($i = 1; $i <= 12 ; $i++)
                    <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                        {{ int_to_month($i) }}
                    </option>
                    @endfor
                </select>

                <select name="year" id="calendar__year">
                    @for($i = date('Y'); $i <= date('Y')+10 ; $i++)
                    <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                    @endfor
                </select>
            </div>

            <div class="calendar__body">
            <div class="calendar__days">
                @foreach ($dayShort as $dn)
                    <div>{{ $dn }}</div>
                @endforeach
            </div>

            <div class="calendar__dates">
                @foreach ($schedule as $day => $detail)
                    @if($day == 1)
                        @for ($i = 0; $i<=day_to_num($detail["day_name"])-1; $i++)
                        <div class="calendar__date calendar__date--grey"><span> </span></div>
                        @endfor
                    @endif
                    <div class="calendar__date
                    @if($todayDay == $day && $todayMonth == $month && $todayYear == $year)
                    calendar__date--range-start @endif"
                    id="d-{{ $day }}">
                    <span>{{ $day }}</span></div>
                    @php
                        $shift =
                       "Group A ".translate_pattern($detail["shift"]["A"])."<hr>".
                        "Group B ".translate_pattern($detail["shift"]["B"])."<hr>".
                        "Group C ".translate_pattern($detail["shift"]["C"])."<hr>".
                        "Group D ".translate_pattern($detail["shift"]["D"]);
                    @endphp
                    <script>
                        tippy('#d-{{ $day }}', {
                        content: '{!! $shift !!}',
                        allowHTML: true,
                        trigger: 'click',
                        });
                    </script>
                @endforeach
            </div>
            </div>

            <div class="calendar__buttons">
                <a class="calendar__button calendar__button--grey"
                href="?all=1">Semua Jdwl</a>
                <button class="calendar__button calendar__button--primary">Terapkan</button>
            </div>
        </form>
    </div>
    @endif
</div>
@endsection
