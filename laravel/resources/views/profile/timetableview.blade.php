@extends('layouts.profile')

@section('view')
    <div class="timetable-wrapper">
        <div class="timetable-header">
            <div class="timetable-person">
                <div class="timetable-h">График работы:</div>
                <div class="timetable-h-name">Вячеслав Борисов</div>
            </div>
            <div class="timetable-type">
                <a href="" class="timetable-btn-left"></a>
                <div class="timetable-btn __month __active">Апрель</div>
                <div class="timetable-btn __year">2021</div>
                <a href="" class="timetable-btn-right"></a>
            </div>
            <div class="timetable-option">
                <div class="timetable-option-noselect">
                    <a href="">Скачать график</a>
                    <a href="">Выбрать даты</a>
                </div>
                <div class="timetable-option-selected __active">
                    <a href="" class="blue-btn">Редактировать</a>
                    <a href="" class="ghost-btn">Отмена</a>
                </div>
            </div>
        </div>
        <div class="timetable-cnt">
            <div class="timetable-type-month __edit">
                <table width="100%" border="1" cellpadding="15" bordercolor="#e8ecef" cellspacing="0">
                    <tbody>
                    <tr>
                        <th width="17%">Пн</th>
                        <th width="17%">Вт</th>
                        <th width="17%">Ср</th>
                        <th width="17%">Чт</th>
                        <th width="17%">Пт</th>
                        <th width="7%">Сб</th>
                        <th width="7%">Вс</th>
                    </tr>
                    <tr>
                        <td>
                            <div class="timetable-type-month-area last-month">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-1" disabled type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-1"></label></div>
                                    <div class="timetable-type-month-num">29</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>
                                </div>
                                <div class="timetable-type-month-icons">
                                    <!-- <div class="timetable-type-month-icon one ic-business"></div>
                                    <div class="timetable-type-month-icon two ic-house"></div>
                                    <div class="timetable-type-month-icon three ic-none"></div> -->
                                    <div class="timetable-type-month-icon four ic-office"></div>
                                    <!-- <div class="timetable-type-month-icon five ic-sick"></div>
                                    <div class="timetable-type-month-icon six ic-social"></div>
                                    <div class="timetable-type-month-icon seven ic-vacation"></div> -->
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="timetable-type-month-area last-month">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-2" disabled type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-2"></label></div>
                                    <div class="timetable-type-month-num">30</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>
                                </div>
                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-office"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="timetable-type-month-area last-month">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-3" disabled type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-3"></label></div>
                                    <div class="timetable-type-month-num">31</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>
                                </div>
                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-office"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-4" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-4"></label></div>
                                    <div class="timetable-type-month-num">1</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>
                                </div>
                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-office"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-5" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-5"></label></div>
                                    <div class="timetable-type-month-num">2</div>
                                    <div class="timetable-type-month-time __hidden">9:30 - 18:00</div>
                                </div>
                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon six ic-social"></div>
                                </div>
                            </div>
                        </td>
                        <td class="__weekend">
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-checkbox"><input id="checkbox-6" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-6"></label></div>
                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-none"></div>
                                </div>
                            </div>
                        </td>
                        <td class="__weekend">
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-checkbox"><input id="checkbox-7" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-7"></label></div>
                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-none"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-8" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-8"></label></div>
                                    <div class="timetable-type-month-num">5</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>
                                </div>
                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon two ic-house"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-9" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-9"></label></div>
                                    <div class="timetable-type-month-num">6</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>
                                </div>
                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon two ic-house"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-10" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-10"></label></div>
                                    <div class="timetable-type-month-num">7</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>
                                </div>

                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-office"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-11" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-11"></label></div>
                                    <div class="timetable-type-month-num">8</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>
                                </div>

                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-office"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="timetable-type-month-area __active">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-12" checked type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-12"></label></div>
                                    <div class="timetable-type-month-num">9</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>
                                </div>

                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-office"></div>
                                </div>
                            </div>
                        </td>
                        <td class="__weekend">
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-checkbox"><input id="checkbox-13" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-13"></label></div>
                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-none"></div>
                                </div>
                            </div>
                        </td>
                        <td class="__weekend">
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-checkbox"><input id="checkbox-14" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-14"></label></div>
                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-none"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-15" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-15"></label></div>
                                    <div class="timetable-type-month-num">12</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>
                                </div>

                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-office"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-16" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-16"></label></div>
                                    <div class="timetable-type-month-num">13</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>
                                </div>

                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-office"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-17" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-17"></label></div>
                                    <div class="timetable-type-month-num">14</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>
                                </div>

                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-office"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-18" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-18"></label></div>
                                    <div class="timetable-type-month-num">15</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>
                                </div>

                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon one ic-business"></div>
                                    <div class="timetable-type-month-icon two ic-office"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-19" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-19"></label></div>
                                    <div class="timetable-type-month-num">16</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>
                                </div>

                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-office"></div>
                                </div>
                            </div>
                        </td>
                        <td class="__weekend">
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-checkbox"><input id="checkbox-20" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-20"></label></div>
                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-none"></div>
                                </div>
                            </div>
                        </td>
                        <td class="__weekend">
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-checkbox"><input id="checkbox-21" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-21"></label></div>
                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-none"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-22" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-22"></label></div>
                                    <div class="timetable-type-month-num">19</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>
                                </div>

                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-office"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-23" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-23"></label></div>
                                    <div class="timetable-type-month-num">20</div>
                                    <div class="timetable-type-month-time __hidden">9:30 - 18:00</div>
                                </div>

                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon five ic-sick"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-24" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-24"></label></div>
                                    <div class="timetable-type-month-num">21</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>
                                </div>

                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-office"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-25" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-25"></label></div>
                                    <div class="timetable-type-month-num">22</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>
                                </div>

                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-office"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-26" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-26"></label></div>
                                    <div class="timetable-type-month-num">23</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>
                                </div>

                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-office"></div>
                                </div>
                            </div>
                        </td>
                        <td class="__weekend">
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-checkbox"><input id="checkbox-27" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-27"></label></div>
                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-none"></div>
                                </div>
                            </div>
                        </td>
                        <td class="__weekend">
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-checkbox"><input id="checkbox-28" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-28"></label></div>
                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon ic-none"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-29" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-29"></label></div>
                                    <div class="timetable-type-month-num">26</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>
                                </div>

                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon seven ic-vacation"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-30" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-30"></label></div>
                                    <div class="timetable-type-month-num">27</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>
                                </div>

                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon seven ic-vacation"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-31" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-31"></label></div>
                                    <div class="timetable-type-month-num">28</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>
                                </div>

                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon seven ic-vacation"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-32" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-32"></label></div>
                                    <div class="timetable-type-month-num">29</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>

                                </div>
                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon seven ic-vacation"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-area-wrap">
                                    <div class="timetable-type-month-checkbox"><input id="checkbox-33" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-33"></label></div>
                                    <div class="timetable-type-month-num">30</div>
                                    <div class="timetable-type-month-time">9:30 - 18:00</div>

                                </div>
                                <div class="timetable-type-month-icons">
                                    <div class="timetable-type-month-icon seven ic-vacation"></div>
                                </div>
                            </div>
                        </td>
                        <td class="__weekend">
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-checkbox"><input id="checkbox-34" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-34"></label></div>
                                <div class="timetable-type-month-icons __weekend-opacity">
                                    <div class="timetable-type-month-icon seven ic-vacation"></div>
                                </div>
                            </div>
                        </td>
                        <td class="__weekend">
                            <div class="timetable-type-month-area">
                                <div class="timetable-type-month-checkbox"><input id="checkbox-35" type="checkbox" class="timetable-checkbox-custom"><label class="timetable-checkbox-label" for="checkbox-35"></label></div>
                                <div class="timetable-type-month-icons __weekend-opacity">
                                    <div class="timetable-type-month-icon seven ic-vacation"></div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
            <div class="timetable-type-year __hidden">
                <div class="timetable-month">
                    <div class="timetable-name">
                        Январь
                    </div>
                    <div class="timetable-days">
                        <table cellspacing="0">
                            <tbody>
                            <tr>
                                <th>Пн</th>
                                <th>Вт</th>
                                <th>Ср</th>
                                <th>Чт</th>
                                <th>Пт</th>
                                <th>Сб</th>
                                <th>Вс</th>
                            </tr>
                            <tr>
                                <td>
                                    <div title="" class="day last">26</div>
                                </td>
                                <td>
                                    <div title="" class="day last">27</div>
                                </td>
                                <td>
                                    <div title="" class="day last">28</div>
                                </td>
                                <td>
                                    <div title="" class="day last">29</div>
                                </td>
                                <td>
                                    <div title="" class="day last">30</div>
                                </td>
                                <td>
                                    <div title="" class="day last">31</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">1</div>
                                </td>
                            </tr>
                            <tr>
                                <td><div title="Выходной" class="day weekend">2</div></td>
                                <td><div title="Выходной" class="day weekend">3</div></td>
                                <td><div title="Выходной" class="day weekend">4</div></td>
                                <td><div title="Выходной" class="day weekend">5</div></td>
                                <td><div title="Выходной" class="day weekend">6</div></td>
                                <td><div title="Выходной" class="day weekend">7</div></td>
                                <td><div title="Выходной" class="day weekend">8</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">9</div></td>
                                <td><div title="В офисе" class="day office">10</div></td>
                                <td><div title="В офисе" class="day office">11</div></td>
                                <td><div title="В офисе" class="day office">12</div></td>
                                <td><div title="В офисе" class="day office">13</div></td>
                                <td><div title="Выходной" class="day weekend">14</div></td>
                                <td><div title="Выходной" class="day weekend">15</div></td>
                            </tr>
                            <tr>
                                <td><div title="Социальный день" class="day social">16</div></td>
                                <td><div title="В офисе" class="day office">17</div></td>
                                <td><div title="В офисе" class="day office">18</div></td>
                                <td><div title="В офисе" class="day office">19</div></td>
                                <td><div title="В офисе" class="day office">20</div></td>
                                <td><div title="Выходной" class="day weekend">21</div></td>
                                <td><div title="Выходной" class="day weekend">22</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">23</div></td>
                                <td><div title="В офисе" class="day office">24</div></td>
                                <td><div title="В офисе" class="day office">25</div></td>
                                <td><div title="В офисе" class="day office">26</div></td>
                                <td><div title="В офисе" class="day office">27</div></td>
                                <td><div title="Выходной" class="day weekend">28</div></td>
                                <td><div title="Выходной" class="day weekend">29</div></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="timetable-month">
                    <div class="timetable-name">
                        Февраль
                    </div>
                    <div class="timetable-days">
                        <table cellspacing="0">
                            <tbody>
                            <tr>
                                <th>Пн</th>
                                <th>Вт</th>
                                <th>Ср</th>
                                <th>Чт</th>
                                <th>Пт</th>
                                <th>Сб</th>
                                <th>Вс</th>
                            </tr>
                            <tr>
                                <td>
                                    <div title="Удаленно из дома" class="day home">26</div>
                                </td>
                                <td>
                                    <div title="Удаленно из дома" class="day home">27</div>
                                </td>
                                <td>
                                    <div title="Удаленно из дома" class="day home">28</div>
                                </td>
                                <td>
                                    <div title="Удаленно из дома" class="day home">29</div>
                                </td>
                                <td>
                                    <div title="Удаленно из дома" class="day home">30</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">31</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">1</div>
                                </td>
                            </tr>
                            <tr>
                                <td><div title="Удаленно из дома" class="day home">2</div></td>
                                <td><div title="Удаленно из дома" class="day home">3</div></td>
                                <td><div title="Удаленно из дома" class="day home">4</div></td>
                                <td><div title="Удаленно из дома" class="day home">5</div></td>
                                <td><div title="Удаленно из дома" class="day home">6</div></td>
                                <td><div title="Выходной" class="day weekend">7</div></td>
                                <td><div title="Выходной" class="day weekend">8</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">9</div></td>
                                <td><div title="Командировка" class="day business">10</div></td>
                                <td><div title="В офисе" class="day office">11</div></td>
                                <td><div title="В офисе" class="day office">12</div></td>
                                <td><div title="В офисе" class="day office">13</div></td>
                                <td><div title="Выходной" class="day weekend">14</div></td>
                                <td><div title="Выходной" class="day weekend">15</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">16</div></td>
                                <td><div title="В офисе" class="day office">17</div></td>
                                <td><div title="В офисе" class="day office">18</div></td>
                                <td><div title="Командировка" class="day business">19</div></td>
                                <td><div title="В офисе" class="day office">20</div></td>
                                <td><div title="Выходной" class="day weekend">21</div></td>
                                <td><div title="Выходной" class="day weekend">22</div></td>
                            </tr>
                            <tr>
                                <td><div title="Выходной" class="day weekend">23</div></td>
                                <td><div title="В офисе" class="day office">24</div></td>
                                <td><div title="В офисе" class="day office">25</div></td>
                                <td><div title="В офисе" class="day office">26</div></td>
                                <td><div title="В офисе" class="day office">27</div></td>
                                <td><div title="Выходной" class="day weekend">28</div></td>
                                <td><div title="Выходной" class="day weekend">29</div></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="timetable-month">
                    <div class="timetable-name">
                        Март
                    </div>
                    <div class="timetable-days">
                        <table cellspacing="0">
                            <tbody>
                            <tr>
                                <th>Пн</th>
                                <th>Вт</th>
                                <th>Ср</th>
                                <th>Чт</th>
                                <th>Пт</th>
                                <th>Сб</th>
                                <th>Вс</th>
                            </tr>
                            <tr>
                                <td>
                                    <div title="В офисе" class="day last office">26</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day office last">27</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">28</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">29</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">30</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">31</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">1</div>
                                </td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">2</div></td>
                                <td><div title="В офисе" class="day office">3</div></td>
                                <td><div title="В офисе" class="day office">4</div></td>
                                <td><div title="В офисе" class="day office">5</div></td>
                                <td><div title="В офисе" class="day office">6</div></td>
                                <td><div title="Выходной" class="day weekend">7</div></td>
                                <td><div title="Выходной" class="day weekend">8</div></td>
                            </tr>
                            <tr>
                                <td><div title="Выходной" class="day weekend">9</div></td>
                                <td><div title="В офисе" class="day office">10</div></td>
                                <td><div title="В офисе" class="day office">11</div></td>
                                <td><div title="В офисе" class="day office">12</div></td>
                                <td><div title="В офисе" class="day office">13</div></td>
                                <td><div title="Выходной" class="day weekend">14</div></td>
                                <td><div title="Выходной" class="day weekend">15</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">16</div></td>
                                <td><div title="В офисе" class="day office">17</div></td>
                                <td><div title="В офисе" class="day office">18</div></td>
                                <td><div title="В офисе" class="day office">19</div></td>
                                <td><div title="В офисе" class="day office">20</div></td>
                                <td><div title="Выходной" class="day weekend">21</div></td>
                                <td><div title="Выходной" class="day weekend">22</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">23</div></td>
                                <td><div title="В офисе" class="day office">24</div></td>
                                <td><div title="Командировка" class="day business">25</div></td>
                                <td><div title="В офисе" class="day office">26</div></td>
                                <td><div title="В офисе" class="day office">27</div></td>
                                <td><div title="Выходной" class="day weekend">28</div></td>
                                <td><div title="Выходной" class="day weekend">29</div></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="timetable-month">
                    <div class="timetable-name">
                        Апрель
                    </div>
                    <div class="timetable-days">
                        <table cellspacing="0">
                            <tbody>
                            <tr>
                                <th>Пн</th>
                                <th>Вт</th>
                                <th>Ср</th>
                                <th>Чт</th>
                                <th>Пт</th>
                                <th>Сб</th>
                                <th>Вс</th>
                            </tr>
                            <tr>
                                <td>
                                    <div title="В офисе" class="day last office">26</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day office last">27</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">28</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">29</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">30</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">31</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">1</div>
                                </td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">2</div></td>
                                <td><div title="В офисе" class="day office">3</div></td>
                                <td><div title="В офисе" class="day office">4</div></td>
                                <td><div title="В офисе" class="day office today">5</div></td>
                                <td><div title="В офисе" class="day office">6</div></td>
                                <td><div title="Выходной" class="day weekend">7</div></td>
                                <td><div title="Выходной" class="day weekend">8</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">9</div></td>
                                <td><div title="В офисе" class="day office">10</div></td>
                                <td><div title="Больничный" class="day sick">11</div></td>
                                <td><div title="Больничный" class="day sick">12</div></td>
                                <td><div title="Больничный" class="day sick">13</div></td>
                                <td><div title="Выходной" class="day weekend">14</div></td>
                                <td><div title="Выходной" class="day weekend">15</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">16</div></td>
                                <td><div title="В офисе" class="day office">17</div></td>
                                <td><div title="В офисе" class="day office">18</div></td>
                                <td><div title="В офисе" class="day office">19</div></td>
                                <td><div title="В офисе" class="day office">20</div></td>
                                <td><div title="Выходной" class="day weekend">21</div></td>
                                <td><div title="Выходной" class="day weekend">22</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">23</div></td>
                                <td><div title="В офисе" class="day office">24</div></td>
                                <td><div title="В офисе" class="day office">25</div></td>
                                <td><div title="В офисе" class="day office">26</div></td>
                                <td><div title="В офисе" class="day office">27</div></td>
                                <td><div title="Выходной" class="day weekend">28</div></td>
                                <td><div title="Выходной" class="day weekend">29</div></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="timetable-month">
                    <div class="timetable-name">
                        Май
                    </div>
                    <div class="timetable-days">
                        <table cellspacing="0">
                            <tbody>
                            <tr>
                                <th>Пн</th>
                                <th>Вт</th>
                                <th>Ср</th>
                                <th>Чт</th>
                                <th>Пт</th>
                                <th>Сб</th>
                                <th>Вс</th>
                            </tr>
                            <tr>
                                <td>
                                    <div title="В офисе" class="day last office">26</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day office last">27</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">28</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">29</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">30</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">31</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">1</div>
                                </td>
                            </tr>
                            <tr>
                                <td><div title="Выходной" class="day weekend">2</div></td>
                                <td><div title="Выходной" class="day weekend">3</div></td>
                                <td><div title="В офисе" class="day office">4</div></td>
                                <td><div title="В офисе" class="day office">5</div></td>
                                <td><div title="В офисе" class="day office">6</div></td>
                                <td><div title="Выходной" class="day weekend">7</div></td>
                                <td><div title="Выходной" class="day weekend">8</div></td>
                            </tr>
                            <tr>
                                <td><div title="Выходной" class="day weekend">9</div></td>
                                <td><div title="Выходной" class="day weekend __active">10</div></td>
                                <td><div title="В офисе" class="day office __active">11</div></td>
                                <td><div title="В офисе" class="day office __active">12</div></td>
                                <td><div title="В офисе" class="day office __active">13</div></td>
                                <td><div title="Выходной" class="day weekend">14</div></td>
                                <td><div title="Выходной" class="day weekend">15</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">16</div></td>
                                <td><div title="В офисе" class="day office">17</div></td>
                                <td><div title="В офисе" class="day office">18</div></td>
                                <td><div title="В офисе" class="day office">19</div></td>
                                <td><div title="В офисе" class="day office">20</div></td>
                                <td><div title="Выходной" class="day weekend">21</div></td>
                                <td><div title="Выходной" class="day weekend">22</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">23</div></td>
                                <td><div title="Командировка" class="day business">24</div></td>
                                <td><div title="В офисе" class="day office">25</div></td>
                                <td><div title="В офисе" class="day office">26</div></td>
                                <td><div title="В офисе" class="day office">27</div></td>
                                <td><div title="Выходной" class="day weekend">28</div></td>
                                <td><div title="Выходной" class="day weekend">29</div></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="timetable-month">
                    <div class="timetable-name">
                        Июнь
                    </div>
                    <div class="timetable-days">
                        <table cellspacing="0">
                            <tbody>
                            <tr>
                                <th>Пн</th>
                                <th>Вт</th>
                                <th>Ср</th>
                                <th>Чт</th>
                                <th>Пт</th>
                                <th>Сб</th>
                                <th>Вс</th>
                            </tr>
                            <tr>
                                <td>
                                    <div title="В офисе" class="day last office">26</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day office last">27</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">28</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">29</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">30</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">31</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">1</div>
                                </td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">2</div></td>
                                <td><div title="В офисе" class="day office">3</div></td>
                                <td><div title="В офисе" class="day office">4</div></td>
                                <td><div title="В офисе" class="day office">5</div></td>
                                <td><div title="Социальный день" class="day social">6</div></td>
                                <td><div title="Выходной" class="day weekend">7</div></td>
                                <td><div title="Выходной" class="day weekend">8</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">9</div></td>
                                <td><div title="В офисе" class="day office">10</div></td>
                                <td><div title="В офисе" class="day office">11</div></td>
                                <td><div title="В офисе" class="day office">12</div></td>
                                <td><div title="В офисе" class="day office">13</div></td>
                                <td><div title="Выходной" class="day weekend">14</div></td>
                                <td><div title="Выходной" class="day weekend">15</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">16</div></td>
                                <td><div title="В офисе" class="day office">17</div></td>
                                <td><div title="В офисе" class="day office">18</div></td>
                                <td><div title="В офисе" class="day office">19</div></td>
                                <td><div title="В офисе" class="day office">20</div></td>
                                <td><div title="В офисе" class="day office">21</div></td>
                                <td><div title="Выходной" class="day weekend">22</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">23</div></td>
                                <td><div title="В офисе" class="day office">24</div></td>
                                <td><div title="В офисе" class="day office">25</div></td>
                                <td><div title="В офисе" class="day office">26</div></td>
                                <td><div title="В офисе" class="day office">27</div></td>
                                <td><div title="Выходной" class="day weekend">28</div></td>
                                <td><div title="Выходной" class="day weekend">29</div></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="timetable-month">
                    <div class="timetable-name">
                        Июль
                    </div>
                    <div class="timetable-days">
                        <table cellspacing="0">
                            <tbody>
                            <tr>
                                <th>Пн</th>
                                <th>Вт</th>
                                <th>Ср</th>
                                <th>Чт</th>
                                <th>Пт</th>
                                <th>Сб</th>
                                <th>Вс</th>
                            </tr>
                            <tr>
                                <td>
                                    <div title="В офисе" class="day last office">26</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day office last">27</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">28</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">29</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">30</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">31</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">1</div>
                                </td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">2</div></td>
                                <td><div title="В офисе" class="day office">3</div></td>
                                <td><div title="Выходной" class="day weekend">4</div></td>
                                <td><div title="В офисе" class="day office">5</div></td>
                                <td><div title="В офисе" class="day office">6</div></td>
                                <td><div title="Выходной" class="day weekend">7</div></td>
                                <td><div title="Выходной" class="day weekend">8</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">9</div></td>
                                <td><div title="В офисе" class="day office">10</div></td>
                                <td><div title="В офисе" class="day office">11</div></td>
                                <td><div title="В офисе" class="day office">12</div></td>
                                <td><div title="В офисе" class="day office">13</div></td>
                                <td><div title="Выходной" class="day weekend">14</div></td>
                                <td><div title="Выходной" class="day weekend">15</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">16</div></td>
                                <td><div title="В офисе" class="day office">17</div></td>
                                <td><div title="В офисе" class="day office">18</div></td>
                                <td><div title="В офисе" class="day office">19</div></td>
                                <td><div title="В офисе" class="day office">20</div></td>
                                <td><div title="Выходной" class="day weekend">21</div></td>
                                <td><div title="Выходной" class="day weekend">22</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">23</div></td>
                                <td><div title="В офисе" class="day office">24</div></td>
                                <td><div title="В офисе" class="day office">25</div></td>
                                <td><div title="В офисе" class="day office">26</div></td>
                                <td><div title="В офисе" class="day office">27</div></td>
                                <td><div title="Выходной" class="day weekend">28</div></td>
                                <td><div title="Выходной" class="day weekend">29</div></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="timetable-month">
                    <div class="timetable-name">
                        Август
                    </div>
                    <div class="timetable-days">
                        <table cellspacing="0">
                            <tbody>
                            <tr>
                                <th>Пн</th>
                                <th>Вт</th>
                                <th>Ср</th>
                                <th>Чт</th>
                                <th>Пт</th>
                                <th>Сб</th>
                                <th>Вс</th>
                            </tr>
                            <tr>
                                <td>
                                    <div title="В офисе" class="day last office">26</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day office last">27</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">28</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">29</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">30</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">31</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">1</div>
                                </td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">2</div></td>
                                <td><div title="В офисе" class="day office">3</div></td>
                                <td><div title="В офисе" class="day office">4</div></td>
                                <td><div title="В офисе" class="day office">5</div></td>
                                <td><div title="В офисе" class="day office">6</div></td>
                                <td><div title="Выходной" class="day weekend">7</div></td>
                                <td><div title="Выходной" class="day weekend">8</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">9</div></td>
                                <td><div title="В офисе" class="day office">10</div></td>
                                <td><div title="В офисе" class="day office">11</div></td>
                                <td><div title="В офисе" class="day office">12</div></td>
                                <td><div title="В офисе" class="day office">13</div></td>
                                <td><div title="Выходной" class="day weekend">14</div></td>
                                <td><div title="Выходной" class="day weekend">15</div></td>
                            </tr>
                            <tr>
                                <td><div title="Отпуск" class="day vacation">16</div></td>
                                <td><div title="Отпуск" class="day vacation">17</div></td>
                                <td><div title="Отпуск" class="day vacation">18</div></td>
                                <td><div title="Отпуск" class="day vacation">19</div></td>
                                <td><div title="Отпуск" class="day vacation">20</div></td>
                                <td><div title="Отпуск" class="day vacation">21</div></td>
                                <td><div title="Отпуск" class="day vacation">22</div></td>
                            </tr>
                            <tr>
                                <td><div title="Отпуск" class="day vacation">23</div></td>
                                <td><div title="Отпуск" class="day vacation">24</div></td>
                                <td><div title="Отпуск" class="day vacation">25</div></td>
                                <td><div title="Отпуск" class="day vacation">26</div></td>
                                <td><div title="Отпуск" class="day vacation">27</div></td>
                                <td><div title="Отпуск" class="day vacation">28</div></td>
                                <td><div title="Отпуск" class="day vacation">29</div></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="timetable-month">
                    <div class="timetable-name">
                        Сентябрь
                    </div>
                    <div class="timetable-days">
                        <table cellspacing="0">
                            <tbody>
                            <tr>
                                <th>Пн</th>
                                <th>Вт</th>
                                <th>Ср</th>
                                <th>Чт</th>
                                <th>Пт</th>
                                <th>Сб</th>
                                <th>Вс</th>
                            </tr>
                            <tr>
                                <td>
                                    <div title="В офисе" class="day last office">26</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day office last">27</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">28</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">29</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">30</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">31</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">1</div>
                                </td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">2</div></td>
                                <td><div title="В офисе" class="day office">3</div></td>
                                <td><div title="В офисе" class="day office">4</div></td>
                                <td><div title="В офисе" class="day office">5</div></td>
                                <td><div title="В офисе" class="day office">6</div></td>
                                <td><div title="Выходной" class="day weekend">7</div></td>
                                <td><div title="Выходной" class="day weekend">8</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">9</div></td>
                                <td><div title="В офисе" class="day office">10</div></td>
                                <td><div title="В офисе" class="day office">11</div></td>
                                <td><div title="В офисе" class="day office">12</div></td>
                                <td><div title="В офисе" class="day office">13</div></td>
                                <td><div title="Выходной" class="day weekend">14</div></td>
                                <td><div title="Выходной" class="day weekend">15</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">16</div></td>
                                <td><div title="В офисе" class="day office">17</div></td>
                                <td><div title="В офисе" class="day office">18</div></td>
                                <td><div title="В офисе" class="day office">19</div></td>
                                <td><div title="В офисе" class="day office">20</div></td>
                                <td><div title="Выходной" class="day weekend">21</div></td>
                                <td><div title="Выходной" class="day weekend">22</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">23</div></td>
                                <td><div title="В офисе" class="day office">24</div></td>
                                <td><div title="В офисе" class="day office">25</div></td>
                                <td><div title="В офисе" class="day office">26</div></td>
                                <td><div title="В офисе" class="day office">27</div></td>
                                <td><div title="Выходной" class="day weekend">28</div></td>
                                <td><div title="Выходной" class="day weekend">29</div></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="timetable-month">
                    <div class="timetable-name">
                        Октябрь
                    </div>
                    <div class="timetable-days">
                        <table cellspacing="0">
                            <tbody>
                            <tr>
                                <th>Пн</th>
                                <th>Вт</th>
                                <th>Ср</th>
                                <th>Чт</th>
                                <th>Пт</th>
                                <th>Сб</th>
                                <th>Вс</th>
                            </tr>
                            <tr>
                                <td>
                                    <div title="В офисе" class="day last office">26</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day office last">27</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">28</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">29</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">30</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">31</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">1</div>
                                </td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">2</div></td>
                                <td><div title="В офисе" class="day office">3</div></td>
                                <td><div title="В офисе" class="day office">4</div></td>
                                <td><div title="В офисе" class="day office">5</div></td>
                                <td><div title="В офисе" class="day office">6</div></td>
                                <td><div title="Выходной" class="day weekend">7</div></td>
                                <td><div title="Выходной" class="day weekend">8</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">9</div></td>
                                <td><div title="В офисе" class="day office">10</div></td>
                                <td><div title="В офисе" class="day office">11</div></td>
                                <td><div title="В офисе" class="day office">12</div></td>
                                <td><div title="В офисе" class="day office">13</div></td>
                                <td><div title="Выходной" class="day weekend">14</div></td>
                                <td><div title="Выходной" class="day weekend">15</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">16</div></td>
                                <td><div title="В офисе" class="day office">17</div></td>
                                <td><div title="В офисе" class="day office">18</div></td>
                                <td><div title="В офисе" class="day office">19</div></td>
                                <td><div title="В офисе" class="day office">20</div></td>
                                <td><div title="Выходной" class="day weekend">21</div></td>
                                <td><div title="Выходной" class="day weekend">22</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">23</div></td>
                                <td><div title="В офисе" class="day office">24</div></td>
                                <td><div title="В офисе" class="day office">25</div></td>
                                <td><div title="В офисе" class="day office">26</div></td>
                                <td><div title="В офисе" class="day office">27</div></td>
                                <td><div title="Выходной" class="day weekend">28</div></td>
                                <td><div title="Выходной" class="day weekend">29</div></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="timetable-month">
                    <div class="timetable-name">
                        Ноябрь
                    </div>
                    <div class="timetable-days">
                        <table cellspacing="0">
                            <tbody>
                            <tr>
                                <th>Пн</th>
                                <th>Вт</th>
                                <th>Ср</th>
                                <th>Чт</th>
                                <th>Пт</th>
                                <th>Сб</th>
                                <th>Вс</th>
                            </tr>
                            <tr>
                                <td>
                                    <div title="В офисе" class="day last office">26</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day office last">27</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">28</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">29</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">30</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">31</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">1</div>
                                </td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">2</div></td>
                                <td><div title="В офисе" class="day office">3</div></td>
                                <td><div title="В офисе" class="day office">4</div></td>
                                <td><div title="В офисе" class="day office">5</div></td>
                                <td><div title="В офисе" class="day office">6</div></td>
                                <td><div title="Выходной" class="day weekend">7</div></td>
                                <td><div title="Выходной" class="day weekend">8</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">9</div></td>
                                <td><div title="В офисе" class="day office">10</div></td>
                                <td><div title="В офисе" class="day office">11</div></td>
                                <td><div title="В офисе" class="day office">12</div></td>
                                <td><div title="В офисе" class="day office">13</div></td>
                                <td><div title="Выходной" class="day weekend">14</div></td>
                                <td><div title="Выходной" class="day weekend">15</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">16</div></td>
                                <td><div title="В офисе" class="day office">17</div></td>
                                <td><div title="В офисе" class="day office">18</div></td>
                                <td><div title="Социальный день" class="day social">19</div></td>
                                <td><div title="В офисе" class="day office">20</div></td>
                                <td><div title="Выходной" class="day weekend">21</div></td>
                                <td><div title="Выходной" class="day weekend">22</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">23</div></td>
                                <td><div title="В офисе" class="day office">24</div></td>
                                <td><div title="В офисе" class="day office">25</div></td>
                                <td><div title="В офисе" class="day office">26</div></td>
                                <td><div title="В офисе" class="day office">27</div></td>
                                <td><div title="Выходной" class="day weekend">28</div></td>
                                <td><div title="Выходной" class="day weekend">29</div></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="timetable-month">
                    <div class="timetable-name">
                        Декабрь
                    </div>
                    <div class="timetable-days">
                        <table cellspacing="0">
                            <tbody>
                            <tr>
                                <th>Пн</th>
                                <th>Вт</th>
                                <th>Ср</th>
                                <th>Чт</th>
                                <th>Пт</th>
                                <th>Сб</th>
                                <th>Вс</th>
                            </tr>
                            <tr>
                                <td>
                                    <div title="В офисе" class="day last office">26</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day office last">27</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">28</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">29</div>
                                </td>
                                <td>
                                    <div title="В офисе" class="day last office">30</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">31</div>
                                </td>
                                <td>
                                    <div title="Выходной" class="day weekend">1</div>
                                </td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">2</div></td>
                                <td><div title="В офисе" class="day office">3</div></td>
                                <td><div title="В офисе" class="day office">4</div></td>
                                <td><div title="Социальный день" class="day social">5</div></td>
                                <td><div title="В офисе" class="day office">6</div></td>
                                <td><div title="Выходной" class="day weekend">7</div></td>
                                <td><div title="Выходной" class="day weekend">8</div></td>
                            </tr>
                            <tr>
                                <td><div title="Отпуск" class="day vacation">9</div></td>
                                <td><div title="Отпуск" class="day vacation">10</div></td>
                                <td><div title="Отпуск" class="day vacation">11</div></td>
                                <td><div title="Отпуск" class="day vacation">12</div></td>
                                <td><div title="Отпуск" class="day vacation">13</div></td>
                                <td><div title="Отпуск" class="day vacation">14</div></td>
                                <td><div title="Отпуск" class="day vacation">15</div></td>
                            </tr>
                            <tr>
                                <td><div title="Отпуск" class="day vacation">16</div></td>
                                <td><div title="Отпуск" class="day vacation">17</div></td>
                                <td><div title="Отпуск" class="day vacation">18</div></td>
                                <td><div title="Отпуск" class="day vacation">19</div></td>
                                <td><div title="Отпуск" class="day vacation">20</div></td>
                                <td><div title="Отпуск" class="day vacation">21</div></td>
                                <td><div title="Отпуск" class="day vacation">22</div></td>
                            </tr>
                            <tr>
                                <td><div title="В офисе" class="day office">23</div></td>
                                <td><div title="В офисе" class="day office">24</div></td>
                                <td><div title="В офисе" class="day office">25</div></td>
                                <td><div title="В офисе" class="day office">26</div></td>
                                <td><div title="В офисе" class="day office">27</div></td>
                                <td><div title="Выходной" class="day weekend">28</div></td>
                                <td><div title="Выходной" class="day weekend">29</div></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="width: auto; height: auto; position: fixed; bottom: 0; right: 0; top: 0; left: 0; z-index: 8010; background-color: rgb(47 47 47 / 40%); display: block;">
        <div class="timetable-modal">
            <div class="timetable-modal-header">
                <div class="timetable-modal-h">График работы на 7 апреля 2021</div>
                <div class="modal-close-ic"></div>
            </div>
            <div class="timetable-modal-cnt">
                <div class="timetable-modal-cnt-time">
                    C <input type="text" size="12" value="9:00" align="middle"> по <input align="middle" type="text" size="12" value="17:00">
                </div>
                <div class="timetable-modal-cnt-form">
                    <div>
                        <label for="office"><input type="radio" id="office" checked name="time1"><div class="ic-office">В офисе</div></label>
                        <label for="house"><input type="radio" id="house" name="time1"><div class="ic-house">Удаленно из дома</div></label>
                        <label for="business"><input type="radio" id="business" name="time1"><div class="ic-business">Коммандировка</div></label>
                    </div>

                    <div>
                        <label for="social"><input type="radio" id="social" name="time1"><div class="ic-social">Социальный день</div></label>
                        <label for="sick"><input type="radio" id="sick" name="time1"><div class="ic-sick">Больничный</div></label>
                        <label for="vacation"><input type="radio" id="vacation" name="time1"><div class="ic-vacation">Отпуск</div></label>
                    </div>
                </div>
            </div>
            <div class="timetable-modal-cnt">
                <div class="timetable-modal-cnt-time">
                    C <input type="text" size="12" value="9:00" align="middle"> по <input align="middle" type="text" size="12" value="17:00">
                </div>
                <div class="timetable-modal-cnt-form">
                    <div>
                        <label for="office"><input type="radio" id="office" checked name="time1"><div class="ic-office">В офисе</div></label>
                        <label for="house"><input type="radio" id="house" name="time1"><div class="ic-house">Удаленно из дома</div></label>
                        <label for="business"><input type="radio" id="business" name="time1"><div class="ic-business">Коммандировка</div></label>
                    </div>

                    <div>
                        <label for="social"><input type="radio" id="social" name="time1"><div class="ic-social">Социальный день</div></label>
                        <label for="sick"><input type="radio" id="sick" name="time1"><div class="ic-sick">Больничный</div></label>
                        <label for="vacation"><input type="radio" id="vacation" name="time1"><div class="ic-vacation">Отпуск</div></label>
                    </div>
                </div>
            </div>
            <div class="timetable-modal-add-wrapper">
                <a href="" class="timetable-modal-add">Добавить временной период</a>
            </div>
            <div class="timetable-modal-footer">
                <a href="" class="blue-btn">Сохранить</a>
                <a href="" class="ghost-btn">Отмена</a>
            </div>
        </div>
    </div>
    <div style="width: auto; height: auto; position: fixed; bottom: 0; right: 0; top: 0; left: 0; z-index: 8010; background-color: rgb(47 47 47 / 40%); display: none;">
        <div class="timetable-modal __download">
            <div class="timetable-modal-header">
                <div class="timetable-modal-h">График работы подразделения</div>
                <div class="modal-close-ic"></div>
            </div>
            <div class="timetable-modal-cnt">
                <div class="timetable-type">
                    <div class="timetable-btn __week">За неделю</div>
                    <div class="timetable-btn __month ">За месяц</div>
                    <div class="timetable-btn __year __active">Выбрать период</div>
                </div>

            </div>
            <div class="timetable-modal-cnt __hidden">
                <div class="timetable-type">
                    <a href="" class="timetable-btn-left"></a>
                    <div class="timetable-btn __basic">С 2 апреля по 9 апреля 2021</div>
                    <a href="" class="timetable-btn-right"></a>
                </div>

            </div>
            <div class="timetable-modal-cnt __hidden">
                <div class="timetable-type">
                    <a href="" class="timetable-btn-left"></a>
                    <div class="timetable-btn __basic">Апрель</div>
                    <a href="" class="timetable-btn-right"></a>
                </div>

            </div>
            <div class="timetable-modal-cnt __period">
                <div class="timetable-type">
                    <a href="" class="timetable-btn-left"></a>
                    <div class="timetable-btn">Апрель</div>
                    <a href="" class="timetable-btn-right"></a>
                    <div class="timetable-modal-month">
                        <table cellspacing="2" width="100%">
                            <tbody>
                            <tr>
                                <th>Пн</th>
                                <th>Вт</th>
                                <th>Ср</th>
                                <th>Чт</th>
                                <th>Пт</th>
                                <th>Сб</th>
                                <th>Вс</th>
                            </tr>
                            <tr>
                                <td><div class="day last-month">26</div></td>
                                <td><div class="day last-month">27</div></td>
                                <td><div class="day last-month">28</div></td>
                                <td><div class="day last-month">29</div></td>
                                <td><div class="day last-month">30</div></td>
                                <td><div class="day last-month">31</div></td>
                                <td><div class="day">1</div></td>
                            </tr>
                            <tr>
                                <td class="__cheked"><div class="day">2</div></td>
                                <td><div class="day">3</div></td>
                                <td><div class="day">4</div></td>
                                <td><div class="day">5</div></td>
                                <td><div class="day">6</div></td>
                                <td><div class="day">7</div></td>
                                <td><div class="day">8</div></td>
                            </tr>
                            <tr>
                                <td><div class="day">9</div></td>
                                <td><div class="day">10</div></td>
                                <td><div class="day">11</div></td>
                                <td><div class="day">12</div></td>
                                <td><div class="day">13</div></td>
                                <td><div class="day">14</div></td>
                                <td><div class="day">15</div></td>
                            </tr>
                            <tr>
                                <td><div class="day">16</div></td>
                                <td><div class="day">17</div></td>
                                <td><div class="day">18</div></td>
                                <td><div class="day">19</div></td>
                                <td><div class="day">20</div></td>
                                <td><div class="day">21</div></td>
                                <td><div class="day">22</div></td>
                            </tr>
                            <tr>
                                <td><div class="day">23</div></td>
                                <td><div class="day">24</div></td>
                                <td><div class="day">25</div></td>
                                <td><div class="day">26</div></td>
                                <td><div class="day">27</div></td>
                                <td><div class="day">28</div></td>
                                <td><div class="day">29</div></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="timetable-type">
                    <a href="" class="timetable-btn-left"></a>
                    <div class="timetable-btn">Апрель</div>
                    <a href="" class="timetable-btn-right"></a>
                    <div class="timetable-modal-month">
                        <table cellspacing="2" width="100%">
                            <tbody>
                            <tr>
                                <th>Пн</th>
                                <th>Вт</th>
                                <th>Ср</th>
                                <th>Чт</th>
                                <th>Пт</th>
                                <th>Сб</th>
                                <th>Вс</th>
                            </tr>
                            <tr>
                                <td><div class="day last-month">26</div></td>
                                <td><div class="day last-month">27</div></td>
                                <td><div class="day last-month">28</div></td>
                                <td><div class="day last-month">29</div></td>
                                <td><div class="day last-month">30</div></td>
                                <td><div class="day last-month">31</div></td>
                                <td><div class="day">1</div></td>
                            </tr>
                            <tr>
                                <td class="__cheked"><div class="day">2</div></td>
                                <td><div class="day">3</div></td>
                                <td><div class="day">4</div></td>
                                <td><div class="day">5</div></td>
                                <td><div class="day">6</div></td>
                                <td><div class="day">7</div></td>
                                <td><div class="day">8</div></td>
                            </tr>
                            <tr>
                                <td><div class="day">9</div></td>
                                <td><div class="day">10</div></td>
                                <td><div class="day">11</div></td>
                                <td><div class="day">12</div></td>
                                <td><div class="day">13</div></td>
                                <td><div class="day">14</div></td>
                                <td><div class="day">15</div></td>
                            </tr>
                            <tr>
                                <td><div class="day">16</div></td>
                                <td><div class="day">17</div></td>
                                <td><div class="day">18</div></td>
                                <td><div class="day">19</div></td>
                                <td><div class="day">20</div></td>
                                <td><div class="day">21</div></td>
                                <td><div class="day">22</div></td>
                            </tr>
                            <tr>
                                <td><div class="day">23</div></td>
                                <td><div class="day">24</div></td>
                                <td><div class="day">25</div></td>
                                <td><div class="day">26</div></td>
                                <td><div class="day">27</div></td>
                                <td><div class="day">28</div></td>
                                <td><div class="day">29</div></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="timetable-modal-footer">
                <a href="" class="blue-btn __big">Скачать отчет</a>
                <a href="" class="ghost-btn">Отмена</a>
            </div>
        </div>
    </div>
@endsection
