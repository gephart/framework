<?php
/** @var \Gephart\Routing\Route $route */
/** @var \Gephart\Routing\Route $actual_route */
?>

<div class="_gf-line">
    <img class="_gf-line__logo" src="data:image/svg+xml;base64,<?=base64_encode(file_get_contents(__DIR__ . "/assets/img/logo.svg"))?>">


    <div class="_gf-line__box">
        <span class="_gf-line__box__title">
            <strong>
                <?php if ($actual_route): ?>
                    <?=$actual_route->getController()?>::<?=$actual_route->getAction()?></strong>
                <?php else: ?>
                    Unknown route
                <?php endif; ?>
        </span>
    </div>

    <div style="float:right">
        <div class="_gf-line__box _gf-line__box--hover">
            <span class="_gf-line__box__title">

                <img class="_gf-line__ico" src="data:image/svg+xml;base64,<?=base64_encode(file_get_contents(__DIR__ . "/assets/img/ico/headers.svg"))?>">

                Headers (<strong><?=http_response_code()?></strong>)</span>
            <div class="_gf-line__box__hover">
                <table>
                    <?php foreach (headers_list() as $header): ?>
                        <tr>
                            <td><?=explode(":",$header)[0]?>:</td>
                            <td><?=explode(":",$header)[1]?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
        <div class="_gf-line__box _gf-line__box--hover">
            <span class="_gf-line__box__title">

                <img class="_gf-line__ico" src="data:image/svg+xml;base64,<?=base64_encode(file_get_contents(__DIR__ . "/assets/img/ico/routes.svg"))?>">

                Routes</span>
            <div class="_gf-line__box__hover">
                <table>
                    <tr>
                        <th>Rule</th>
                        <th>Action</th>
                        <th>Name</th>
                    </tr>
                    <?php foreach ($routes as $route): ?>
                        <tr>
                            <td><?=$route->getRule()?></td>
                            <td><?=$route->getController()?>::<?=$route->getAction()?></td>
                            <td><?=$route->getName()?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

        <div class="_gf-line__box _gf-line__box--hover">
            <span class="_gf-line__box__title">

                <img class="_gf-line__ico" src="data:image/svg+xml;base64,<?=base64_encode(file_get_contents(__DIR__ . "/assets/img/ico/listeners.svg"))?>">

                Listeners
            </span>
            <div class="_gf-line__box__hover">
                <table>
                    <tr>
                        <th>Event</th>
                        <th>Callback</th>
                        <th>Priority</th>
                    </tr>
                    <?php foreach ($listeners as $listener): ?>
                        <tr>
                            <td><?=$listener["event"]?></td>
                            <td><?php
                            if (is_array($listener["callback"])) {
                                echo get_class($listener["callback"][0]) . "::".$listener["callback"][1];
                            }
                            ?></td>
                            <td><?=$listener["priority"]?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

    <div class="_gf-line__box">
        <span class="_gf-line__box__title">

                <img class="_gf-line__ico" src="data:image/svg+xml;base64,<?=base64_encode(file_get_contents(__DIR__ . "/assets/img/ico/time.svg"))?>">

            Time:
            <strong><?=number_format((microtime(true)-$microtime),4)?>s</strong>
        </span>
    </div>
    </div>
</div>

<style>
    <?=file_get_contents(__DIR__."/assets/css/main.css")?>
</style>