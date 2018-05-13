<?php
get_header();

echo "<div id='oy-unique' class='leftpane person-page'>";
echo '<div id="oy-hide-button" class="oy-rotate"><img src="' . plugins_url('img/arrow.png', __FILE__) . '"/></div>';
echo '<div id="oy-hide-tip">Arama kutusunu açmak için tıklayın.</div>';
echo '<div id="oy-arama-container">
        <h1 class="oy-ayrinti-text"> Ayrıntılı Arama </h1>
          <div class="oy-arama-form">
            <form name="input" action="?name=ayrintili-ara" id="input" method="post" onsubmit="return validateSearch_real()">
              <div class="oy-arama-major-field">
                <h2>Kelimelerinin...</h2>

                <div class="oy-arama-input-field">
                  <div class="oy-arama-text"><p>hepsinin geçtiği:</p></div>
                  <div class="oy-arama-box">
                    <input class="oy-arama-input-big" name="words_included" type="text"/>
                  </div>
                </div>

                <div class="oy-arama-input-field">
                  <div class="oy-arama-text"><p>yan yana geçtiği:</p></div>
                  <div class="oy-arama-box">
                    <input class="oy-arama-input-big" name="words_ordered" type="text"/>
                  </div>
                </div>

                <div class="oy-arama-input-field">
                  <div class="oy-arama-text"><p>en az birinin geçtiği:</p></div>
                  <div class="oy-arama-box">
                    <input class="oy-arama-input-big" name="words_at_least_one" type="text"/>
                  </div>
                </div>

                <div class="oy-arama-input-field">
                  <div class="oy-arama-text"><p>geçmediği:</p></div>
                  <div class="oy-arama-box">
                    <input class="oy-arama-input-big" name="words_excluded" type="text"/>
                  </div>
                </div>
              </div>
              <div class="oy-divider"></div>
              <div class="oy-arama-major-field">
                <h2>Sonuçların...</h2>
                <div class="oy-arama-input-field">
                  <div class="oy-arama-text"><p>yazarı:</p></div>
                  <div class="oy-arama-box">
                    <input class="oy-arama-input-mid" name="author_slug" type="username"/>
                  </div>
                </div>

                <div class="oy-arama-input-field">
                  <div class="oy-arama-text"><p>yayınlanma tarih aralığı:</p></div>
                  <div class="oy-arama-box">
                    <input id="txtFromDate" class="custom_date oy-arama-input-small" name="date_begin" value="" type="text"/> - <input id="txtToDate" class="custom_date oy-arama-input-small" name="date_end" value="" type="text"/>
                  </div>
                </div>

                <div class="oy-arama-input-field">
                  <div class="oy-arama-text"><p>minimum tutulma sayısı:</p></div>
                  <div class="oy-arama-box">
                    <input class="oy-arama-input-small-small" name="likes" pattern="\d*" type="number" value="0">
                  </div>
                </div>
                <div class="oy-arama-input-field">
                  <div class="oy-arama-text"><p>arama yeri:</p></div>
                  <div class="oy-arama-box">
                    <select class="oy-arama-select" id="oy-arama-tur-js-icin" name="search_type">
                      <option value="posts">Yazılar</option>
                      <option value="comments">Yorumlar</option>
                    </select>
                  </div>
                </div>

                <div class="oy-arama-input-field">
                  <div class="oy-arama-text"><p>sırası:</p></div>
                  <div class="oy-arama-box">
                    <select class="oy-arama-select" name="date_order">
                      <option value="desc">Yeniden eskiye</option>
                      <option value="asc">Eskiden yeniye</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="oy-divider"></div>
              <div class="oy-arama-major-field" id="oy-etiket-just-for-yazi">
                <h2>Etiketlerinin...</h2>
                <div class="oy-arama-input-field">
                  <div class="oy-arama-text"><p>en az birinin bulunduğu:</p></div>
                  <div class="oy-arama-box">
                    <input placeholder="(virgülle ayrılmış)" class="oy-arama-big" type="text" name="inc_tags"/>
                  </div>
                </div>
                <div class="oy-arama-input-field">
                  <div class="oy-arama-text"><p>hepsinin bulunduğu:</p></div>
                  <div class="oy-arama-box">
                    <input placeholder="(virgülle ayrılmış)" class="oy-arama-big" type="text" name="inc_tags_all"/>
                  </div>
                </div>
              </div>
              <div class="oy-divider-borderless"></div>
              <div class="oy-arama-major-field">
                <div class="oy-arama-input-field">
                  <input value="Ara" type="submit">
                </div>
              </div>
            <input type="hidden" name="oy_page" value="1"/>
            </form>

          </div>
        </div>';

/*
Storing neccessary post data.
*/
$oy_author_slug         = $_POST["author_slug"];
$oy_date_begin          = $_POST["date_begin"];
$oy_date_end            = $_POST["date_end"];
$oy_words_included      = $_POST["words_included"];
$oy_words_ordered       = $_POST["words_ordered"];
$oy_words_at_least_one  = $_POST["words_at_least_one"];
$oy_words_excluded      = $_POST["words_excluded"];
$oy_order               = $_POST["date_order"];
$oy_likes               = (int)$_POST["likes"];
$oy_type                = $_POST["search_type"];
$oy_tags                = $_POST["inc_tags"];
$oy_tags_all            = $_POST["inc_tags_all"];
$oy_author_id           = NULL;
$oy_page                = (int)$_POST['oy_page'];
$oy_results_per_page    = 20;

if ($oy_page <= 0) {
  $oy_page = 1;
}
/*
If author is relevant to search get author id.
*/
if( $oy_author_slug != NULL ) {
  $oy_author_id = oy_get_userid_by_display_name($oy_author_slug);
}

echo '<div class="oy-arama-sonuc-container">';

/*
Based on the search place,do the things.
- Generate query
- Evaluate query
- Print the results
- Be happy
*/
if ($oy_type == "comments") {
  $query = oy_generate_comment_query($oy_author_slug, $oy_date_begin, $oy_date_end, $oy_words_included, $oy_words_ordered, $oy_words_at_least_one, $oy_words_excluded, $oy_order);
  $result = $query->evaluate_query();

  $oy_word_list = array();

  if ($oy_words_included != NULL) {
    $oy_word_list = array_merge($oy_word_list, explode(" ",$oy_words_included));
  }

  if ($oy_words_at_least_one != NULL) {
    $oy_word_list = array_merge($oy_word_list, explode(" ",$oy_words_at_least_one));
  }

  if ($oy_words_ordered != NULL) {
   array_push($oy_word_list, $oy_words_ordered);
  }

  echo $result["message"];
  oy_print_comments($result["results"], $oy_word_list, $oy_page, $oy_results_per_page);

  } elseif ($oy_type == "posts") {
    $query = oy_generate_post_query($oy_author_id, $oy_date_begin, $oy_date_end, $oy_words_included, $oy_words_ordered, $oy_words_at_least_one, $oy_words_excluded, $oy_order, $oy_likes, $oy_author_slug,$oy_tags,$oy_tags_all);
    $result = $query->evaluate_query();

    $oy_word_list = array();

    if ($oy_words_included != NULL) {
      $oy_word_list = array_merge($oy_word_list, explode(" ", $oy_words_included));
    }

    if ($oy_words_at_least_one != NULL) {
      $oy_word_list = array_merge($oy_word_list, explode(" ", $oy_words_at_least_one));
    }

    if ($oy_words_ordered != NULL) {
     array_push($oy_word_list, $oy_words_ordered);
    }

    echo $result["message"];
    oy_print_posts($result["results"],$oy_word_list, $oy_page, $oy_results_per_page);
 }

echo "</div>";
echo "</div>";

get_sidebar();
get_footer();
?>
