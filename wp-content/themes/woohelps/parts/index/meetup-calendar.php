<?php
/**
 * map-google.php
 *
 * @author      mogita
 * @created_by  PhpStorm
 * @created_at  8/28/16 12:52
 */
?>
<!-- section divider -->
<div class="section-divider">
    <div class="container">
        <div class="row">
            <div class="title">
                <h2 class="text-center">
                    同城活动日历
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="container" style="margin-top: 50px; margin-bottom: 50px;">
    <div class="row">
        <div class="col-xs-12 col-md-8">
            <?php if ( bbp_has_topics() ) : ?>

                <?php
                $query = new WP_Query([
                                          'post_type' => 'topic', // Narrow query down to bbPress topics
                                          'post_parent' => 'any',      // Forum ID
                                          'meta_key' => 'date_and_time',   // changed to orderby activity date and time
                                          'orderby' => 'meta_value_num',              // 'meta_value', 'author', 'date', 'title', 'modified', 'parent', rand',
                                          'order' => 'ASC',                    // 'ASC', 'DESC'
                                          'posts_per_page' => 100, // Topics per page
                                          'paged' => 1,           // Page Number
                                          's' => '',     // Topic Search
                                          'show_stickies' => 1,    // Ignore sticky topics?
                                          'max_num_pages' => false,                     // Maximum number of pages to show
                                          'meta_query' => [
                                              [
                                                  'key' => 'date_and_time',
                                                  'value' => time() * 1000,
                                                  'compare' => '>=' // later than today and now
                                              ]
                                          ]
                                      ]);

                $topics = $query->posts;
                $lastDate = '';
                $daysOfWeek = ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];

                $maxItem = 3;
                $currentItem = 0;

                foreach ($topics as $key => $item) {
                    if ($currentItem >= $maxItem) break;

                    $id = $item->ID;
                    $topics[$key]->subscribers = bbp_get_topic_subscribers($id);

                    $topics[$key]->meta = [];
                    $topics[$key]->meta['date_and_time'] = get_post_meta( $id, 'date_and_time', true);
                    $topics[$key]->meta['organizer'] = get_post_meta( $id, 'organizer', true);
                    $topics[$key]->meta['attendee_count_limit'] = get_post_meta( $id, 'attendee_count_limit', true);
                    $topics[$key]->meta['enroll_deadline'] = get_post_meta( $id, 'enroll_deadline', true);
                    $topics[$key]->meta['fee'] = get_post_meta( $id, 'fee', true);
                    $topics[$key]->meta['location'] = get_post_meta( $id, 'location', true);

                    $topics[$key]->forum = [];
                    $topics[$key]->forum['name'] = get_the_title($topics[$key]->post_parent);
                    $topics[$key]->forum['link'] = get_the_permalink($topics[$key]->post_parent);

                    $theDate = date('m 月 d 日', $topics[$key]->meta['date_and_time'] / 1000);
                    if ($lastDate != $theDate) {
                    if ($lastDate !== '') {
                    ?>
                        </ul><!--  end of list group  -->
                                </div><!--  end of panel  -->
                    <?php
                    }
                    ?>
                    <h4><?=date('m 月 d 日', $topics[$key]->meta['date_and_time'] / 1000) . ' ' . $daysOfWeek[date('w', $topics[$key]->meta['date_and_time'] / 1000)]?></h4>
                            <div class="panel panel-default">
                                <ul class="list-group">
                            <?php
                            $lastDate = $theDate;
                            }
                            ?>
                                    <li class="list-group-item" style="margin-bottom: 1px;">
                                <div class="row">
                                    <div class="col-sm-2 col-xs-12">
                                        <p><?=date('H:i', $topics[$key]->meta['date_and_time'] / 1000)?></p>
                                    </div>
                                    <div class="col-sm-10 col-xs-12">
                                        <a class="text-muted" href="<?=$topics[$key]->forum['link']?>"><?=$topics[$key]->forum['name']?></a>
                                        <h4 style="margin: 5px 0;"><a href="<?=bbp_get_topic_permalink($id)?>"><?=$topics[$key]->post_title?></a></h4>
                                        <p style="margin: 0;">
                                            <strong>地址：</strong><?=$topics[$key]->meta['location']?>
                                        </p>
                                        <p class="text-muted">
                                            <strong>参加人数：</strong><?=count($topics[$key]->subscribers)?> 人
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <?php
                            $currentItem++;
                            } // end of foreach
                            ?>
                        </ul>
                    </div>

        <?php else : ?>

            暂无活动，敬请期待！

        <?php endif; ?>
</div>
        <div class="col-xs-12 col-md-4" style="margin-top: 40px;">
            <?php
            $topics = array();

            if ( bbp_has_topics() ) {
                while ( bbp_topics() ) {
                    bbp_the_topic();

                    $topic_id = bbp_get_topic_id();

                    array_push($topics, [
                        'id' => $topic_id,
                        'title' => bbp_get_topic_title(),
                        'url' => bbp_get_topic_permalink(),
                        'date' => get_post_meta( $topic_id, 'date_and_time', true)
                    ]);
                }
            }

            $calendar = new Calendar($topics);
            echo $calendar->show();
            ?>
        </div>
    </div>
</div>

<script>
	if (typeof $ === 'undefined') var $ = jQuery;
    $(function() {
        $("[data-toggle='popover']").each(function(index, element) {
            var contentElementId = $(element).data().target;
            var contentHtml = $(contentElementId).html();
            $(element).popover({
                content: contentHtml
            });
        });

        $('body').on('click', function(e) {
            if ($(e.target).data('toggle') !== 'popover'
                && $(e.target).parents('.popover.in').length === 0) {
                $('[data-toggle="popover"]').popover('hide');
            }
        });
    });
</script>