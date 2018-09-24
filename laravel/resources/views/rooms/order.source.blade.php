@extends('layouts.static', ['class' => '__order'])

@section('news')
    <div class="reserve">
        <div class="reserve_h">
            <h1 class="h __h_m reserve_h_t">Бронирование: каб. {{$room->name}}</h1>
            <div class="reserve_slide"><a href="" class="reserve_slide_prev"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11.1 19.4"><path d="M9.7 0l1.4 1.4-8.3 8.3 8.3 8.3-1.4 1.4L0 9.7"/></svg></a><span class="reserve_slide_tx">10 сентября &ndash; 16 сентября</span><a href="" class="reserve_slide_next"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11.1 19.4"><path d="M0 1.4L1.4 0l9.7 9.7-9.7 9.7L0 18l8.3-8.3"/></svg></a></div>
        </div>
        <div class="reserve_table">

            <div class="reserve_table_column">
                <div class="reserve_table_column_h">
                    <div class="reserve_table_column_h_date">10 сентября</div>
                    <div class="reserve_table_column_h_weekday">Понедельник</div>
                </div>
                <div class="reserve_table_column_line">9:00</div>
                <div class="reserve_table_column_line">10:00</div>
                <div class="reserve_table_column_line">11:00</div>
                <div class="reserve_table_column_line">12:00</div>
                <div class="reserve_table_column_line">13:00</div>
                <div class="reserve_table_column_line">14:00</div>
                <div class="reserve_table_column_line">15:00</div>
                <div class="reserve_table_column_line">16:00</div>
                <div class="reserve_table_column_line">17:00</div>
                <div class="reserve_table_column_line">18:00</div>
                <div class="reserve_table_column_btn">Забронировать</div>
                <div style="top: 125px; height: 26px;" class="reserve_table_filled __one __collapsed">
                    <div title="Борисов В." class="reserve_table_filled_img"><img src="http://intra-new.dmz/storage/users/faces/7c8IqkMcv9z3lwNk6LHhKmWnsrVej0UbVgvacsuc.jpeg"></div>
                    <div class="reserve_table_filled_cnt">
                        <div class="reserve_table_filled_cnt_bl __ellipsis">Обсуждение приложения для ios</div>
                        <div class="reserve_table_filled_cnt_bl">10:00 &ndash; 10:30</div>
                        <div class="reserve_table_filled_cnt_bl">Борисов В.</div>
                    </div>
                </div>
                <div style="top: 332px; height: 104px;" class="reserve_table_filled __two">
                    <div title="Борисов В." class="reserve_table_filled_img"><img src="http://intra-new.dmz/storage/users/faces/7c8IqkMcv9z3lwNk6LHhKmWnsrVej0UbVgvacsuc.jpeg"></div>
                    <div class="reserve_table_filled_cnt">
                        <div class="reserve_table_filled_cnt_bl">Обсуждение приложения для ios</div>
                        <div class="reserve_table_filled_cnt_bl">14:00 &ndash; 16:00</div>
                        <div class="reserve_table_filled_cnt_bl">Борисов В.</div>
                    </div>
                </div>
            </div>
            <div class="reserve_table_column">
                <div class="reserve_table_column_h">
                    <div class="reserve_table_column_h_date">11 сентября</div>
                    <div class="reserve_table_column_h_weekday">Вторник</div>
                </div>
                <div class="reserve_table_column_line">9:00</div>
                <div class="reserve_table_column_line">10:00</div>
                <div class="reserve_table_column_line">11:00</div>
                <div class="reserve_table_column_line">12:00</div>
                <div class="reserve_table_column_line">13:00</div>
                <div class="reserve_table_column_line">14:00</div>
                <div class="reserve_table_column_line">15:00</div>
                <div class="reserve_table_column_line">16:00</div>
                <div class="reserve_table_column_line">17:00</div>
                <div class="reserve_table_column_line">18:00</div>
                <div class="reserve_table_column_btn">Забронировать</div>
                <div style="top: 320px; height: 104px;" class="reserve_table_filled __three">
                    <div title="Борисов В." class="reserve_table_filled_img"><img src="http://intra-new.dmz/storage/users/faces/7c8IqkMcv9z3lwNk6LHhKmWnsrVej0UbVgvacsuc.jpeg"></div>
                    <div class="reserve_table_filled_cnt">
                        <div class="reserve_table_filled_cnt_bl">Дэдлайн</div>
                        <div class="reserve_table_filled_cnt_bl">13:15 &ndash; 13:30</div>
                        <div class="reserve_table_filled_cnt_bl">Борисов В.</div>
                    </div>
                </div>
            </div>
            <div class="reserve_table_column">
                <div class="reserve_table_column_h">
                    <div class="reserve_table_column_h_date">12 сентября</div>
                    <div class="reserve_table_column_h_weekday">Среда</div>
                </div>
                <div class="reserve_table_column_line">9:00</div>
                <div class="reserve_table_column_line">10:00</div>
                <div class="reserve_table_column_line">11:00</div>
                <div class="reserve_table_column_line">12:00</div>
                <div class="reserve_table_column_line">13:00</div>
                <div class="reserve_table_column_line">14:00</div>
                <div class="reserve_table_column_line">15:00</div>
                <div class="reserve_table_column_line">16:00</div>
                <div class="reserve_table_column_line">17:00</div>
                <div class="reserve_table_column_line">18:00</div>
                <div class="reserve_table_column_btn">Забронировать</div>
                <div style="top: 229px; height: 26px;" class="reserve_table_filled __five __collapsed">
                    <div title="Борисов В." class="reserve_table_filled_img"><img src="http://intra-new.dmz/storage/users/faces/7c8IqkMcv9z3lwNk6LHhKmWnsrVej0UbVgvacsuc.jpeg"></div>
                    <div class="reserve_table_filled_cnt">
                        <div class="reserve_table_filled_cnt_bl __ellipsis">Обсуждение приложения для ios</div>
                        <div class="reserve_table_filled_cnt_bl">10:00 &ndash; 10:30</div>
                        <div class="reserve_table_filled_cnt_bl">Борисов В.</div>
                    </div>
                </div>
                <div style="top: 255px; height: 26px;" class="reserve_table_filled __seven __collapsed">
                    <div title="Борисов В." class="reserve_table_filled_img"><img src="http://intra-new.dmz/storage/users/faces/7c8IqkMcv9z3lwNk6LHhKmWnsrVej0UbVgvacsuc.jpeg"></div>
                    <div class="reserve_table_filled_cnt">
                        <div class="reserve_table_filled_cnt_bl __ellipsis">Обсуждение приложения для ios</div>
                        <div class="reserve_table_filled_cnt_bl">10:00 &ndash; 10:30</div>
                        <div class="reserve_table_filled_cnt_bl">Борисов В.</div>
                    </div>
                </div>
            </div>
            <div class="reserve_table_column">
                <div class="reserve_table_column_h">
                    <div class="reserve_table_column_h_date">13 сентября</div>
                    <div class="reserve_table_column_h_weekday">Четверг</div>
                </div>
                <div class="reserve_table_column_line">9:00</div>
                <div class="reserve_table_column_line">10:00</div>
                <div class="reserve_table_column_line">11:00</div>
                <div class="reserve_table_column_line">12:00</div>
                <div class="reserve_table_column_line">13:00</div>
                <div class="reserve_table_column_line">14:00</div>
                <div class="reserve_table_column_line">15:00</div>
                <div class="reserve_table_column_line">16:00</div>
                <div class="reserve_table_column_line">17:00</div>
                <div class="reserve_table_column_line">18:00</div>
                <div class="reserve_table_column_btn">Забронировать</div>
                <div style="top: 177px; height: 52px;" class="reserve_table_filled __four __collapsed">
                    <div title="Борисов В." class="reserve_table_filled_img"><img src="http://intra-new.dmz/storage/users/faces/7c8IqkMcv9z3lwNk6LHhKmWnsrVej0UbVgvacsuc.jpeg"></div>
                    <div class="reserve_table_filled_cnt">
                        <div class="reserve_table_filled_cnt_bl __ellipsis">Обсуждение приложения для ios</div>
                        <div class="reserve_table_filled_cnt_bl">10:00 &ndash; 10:30</div>
                        <div class="reserve_table_filled_cnt_bl">Борисов В.</div>
                    </div>
                </div>
            </div>
            <div class="reserve_table_column">
                <div class="reserve_table_column_h">
                    <div class="reserve_table_column_h_date">14 сентября</div>
                    <div class="reserve_table_column_h_weekday">Пятница</div>
                </div>
                <div class="reserve_table_column_line">9:00</div>
                <div class="reserve_table_column_line">10:00</div>
                <div class="reserve_table_column_line">11:00</div>
                <div class="reserve_table_column_line">12:00</div>
                <div class="reserve_table_column_line">13:00</div>
                <div class="reserve_table_column_line">14:00</div>
                <div class="reserve_table_column_line">15:00</div>
                <div class="reserve_table_column_line">16:00</div>
                <div class="reserve_table_column_line">17:00</div>
                <div class="reserve_table_column_line">18:00</div>
                <div class="reserve_table_column_btn">Забронировать</div>
                <div style="top: 229px; height: 78px;" class="reserve_table_filled __eight __collapsed">
                    <div title="Борисов В." class="reserve_table_filled_img"><img src="http://intra-new.dmz/storage/users/faces/7c8IqkMcv9z3lwNk6LHhKmWnsrVej0UbVgvacsuc.jpeg"></div>
                    <div class="reserve_table_filled_cnt">
                        <div class="reserve_table_filled_cnt_bl __ellipsis">Обсуждение приложения для ios</div>
                        <div class="reserve_table_filled_cnt_bl">10:00 &ndash; 10:30</div>
                        <div class="reserve_table_filled_cnt_bl">Борисов В.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--eo modal-->
@endsection
