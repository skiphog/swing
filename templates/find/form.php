<?php
/**
 * @var \App\Models\Users\Auth $auth
 */
?>
<form method="get" action="<?php echo url('findresult'); ?>">
    <table width="100%">
        <tr>
            <td width="50%" valign="top">
                <table border="0">
                    <tr class="td-padding">
                        <td width="80"><label for="gender">Ищу</label></td>
                        <td>
                            <select id="gender" name="gender">
                                <option value="0">Не важно</option>
                                <?php foreach(\App\Arrays\Genders::$sgender as $key => $gender){?>
                                    <option value="<?php echo $key; ?>"><?php echo $gender; ?></option>
                                <?php }?>
                            </select>
                        </td>
                    </tr>
                    <tr><td height="1" colspan="2" bgcolor="#336699"></td></tr>
                    <tr class="td-padding">
                        <td><label for="agef">Возраст</td>
                        <td>
                            <label for="agef">от</label>&nbsp;
                            <select id="agef" name="agef">
                                <option  value="18" selected="selected">18</option>
                                <?php for($i = 19;$i < 61;$i++) {?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php }?>
                            </select>
                            &nbsp;<label for="aget">до</label>&nbsp;
                            <select id="aget" name="aget">
                                <option  value="60" selected="selected">60</option>
                                <?php for($i = 18;$i < 60;$i++) {?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php }?>
                            </select>
                        </td>
                    </tr>
                    <tr><td height="1" colspan="2" bgcolor="#336699"></td></tr>
                    <tr class="td-padding">
                        <td><label for="purposes">Цель</label></td>
                        <td>
                            <select id="purposes" name="purposes">
                                <option value="0">Любая</option>
                                <?php foreach(\App\Arrays\Purposes::$array as $key => $purpose) {?>
                                    <option value="<?php echo $key; ?>"><?php echo $purpose; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr><td height="1" colspan="2" bgcolor="#336699"></td></tr>
                    <tr class="td-padding">
                        <td><label for="country">Страна</label></td>
                        <td>
                            <select id="country" name="country">
                                <option value="0">Не важно</option>
                                <?php foreach(\App\Arrays\Country::$array as $key => $country) :?>
                                    <option value="<?php echo $key; ?>" <?php if($auth->country === $key) : ?>selected<?php endif; ?>><?php echo $country; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr><td height="1" colspan="2" bgcolor="#336699"></td></tr>
                    <tr class="td-padding">
                        <td><label for="find_city">Город</label></td>
                        <td>
                            <input id="find_city" type="text" class="text" style="width: 97%;padding: 5px" name="find_city">
                        </td>
                    </tr>
                    <tr><td height="1" colspan="2" bgcolor="#336699"></td></tr>
                    <tr class="td-padding">
                        <td><label for="find_nik">Ник</label></td>
                        <td>
                            <input id="find_nik" type="text" class="text" style="width: 97%;padding: 5px" name="find_nik">
                        </td>
                    </tr>
                    <tr><td height="1" colspan="2" bgcolor="#336699"></td></tr>

                    <tr class="td-padding">
                        <td colspan="2">
                            <label class="find-input"><input type="checkbox" name="find_alb"> Только с фото</label>
                        </td>
                    </tr>
                    <tr><td height="1" colspan="2" bgcolor="#336699"></td></tr>
                    <tr class="td-padding">
                        <td colspan="2">
                            <label class="find-input"><input type="checkbox" name="find_real"> Только реальные</label>
                        </td>
                    </tr>
                    <tr><td height="1" colspan="2" bgcolor="#336699"></td></tr>
                    <tr class="td-padding">
                        <td colspan="2">
                            <label class="find-input"><input type="checkbox" name="find_new"> Сначала новые</label>
                        </td>
                    </tr>
                    <tr><td height="1" colspan="2" bgcolor="#336699"></td></tr>
                    <tr class="td-padding">
                        <td colspan="2">
                            <input class="btn btn-primary" type="submit" value="Искать">
                        </td>
                    </tr>
                </table>
            </td>
            <td width=50% valign=center align=center>

                <table border=0 width=100%>
                    <tr>
                        <td align=center>
                            <a href="<?php echo url('/hotmeet'); ?>"><img class="f-img" src="/img/meet/01.jpg" width="190" height="112" alt="travel"></a>
                        </td>
                        <td align=center>
                            <a href="<?php echo url('/onlinemeet_1_1'); ?>"><img class="f-img" src="/img/meet/02.jpg" width="190" height="112" alt="travel"></a>
                        </td>
                    </tr>
                    <tr>
                        <td align=center>
                            <?php if(auth()->isUser()) :?>
                                <a href="<?php echo url('/nowmeet_1'); ?>"><img class="f-img" src="/img/meet/03.jpg" width="190" height="112" alt="travel"></a>
                            <?php endif; ?>
                        </td>
                        <td align=center>
                            <a href="<?php echo url('/travel'); ?>"><img class="f-img" src="/img/meet/04.jpg" width="190" height="112" alt="travel"></a>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</form>