<?
//
// Pipecode - distributed social network
// Copyright (C) 2014-2015 Bryan Beicker <bryan@pipedot.org>
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Affero General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Affero General Public License for more details.
//
// You should have received a copy of the GNU Affero General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// article
const TYPE_UNKNOWN = 0;
const TYPE_ARTICLE = 1;
const TYPE_BODY = 2;
const TYPE_COMMENT = 3;
const TYPE_COMMENT_VOTE = 4;
const TYPE_VOTE = 5;

// ask
const TYPE_ANSWER = 10;
const TYPE_QUESTION = 11;

// bug
const TYPE_BUG = 20;
const TYPE_BUG_FILE = 21;

// calendar
const TYPE_APPOINTMENT = 30;
const TYPE_CALENDAR = 31;

// drive
const TYPE_CACHE = 40;
const TYPE_DRIVE_DIR = 41;
const TYPE_DRIVE_FILE = 42;

// feed
const TYPE_FEED = 50;
const TYPE_FEED_TOPIC = 51;

// image
const TYPE_GALLERY = 60;
const TYPE_IMAGE = 61;
const TYPE_PHOTO = 62;
const TYPE_SCREENSHOT = 63;
const TYPE_THUMB = 64;

// journal
const TYPE_JOURNAL = 70;
const TYPE_JOURNAL_TOPIC = 71;

// mail
const TYPE_ADDRESS_BOOK = 80;
const TYPE_CONTACT = 81;
const TYPE_LIST = 82;
const TYPE_MAIL = 83;
const TYPE_MAIL_ATTACHMENT = 84;
const TYPE_MAIL_BODY = 85;
const TYPE_MAIL_SIGNATURE = 86;

// music
const TYPE_ALBUM = 90;
const TYPE_COVER = 91;
const TYPE_GENRE = 92;
const TYPE_PLAYLIST = 93;
const TYPE_SONG = 94;

// news
const TYPE_NEWS = 100;
const TYPE_NEWS_GROUP = 101;

// organization
const TYPE_ORGANIZATION = 110;

// poll
const TYPE_POLL = 120;
const TYPE_POLL_ANSWER = 121;

// project
const TYPE_PROJECT = 130;
const TYPE_PROJECT_FILE = 131;
const TYPE_PROJECT_MILESTONE = 132;
const TYPE_PROJECT_RELEASE = 133;

// reader
const TYPE_READER = 140;
const TYPE_READER_TOPIC = 141;

// store
const TYPE_STORE_ANSWER = 150;
const TYPE_STORE_CART = 151;
const TYPE_STORE_CATEGORY = 152;
const TYPE_STORE_FEATURE = 153;
const TYPE_STORE_GALLERY = 154;
const TYPE_STORE_ITEM = 155;
const TYPE_STORE_QUESTION = 156;
const TYPE_STORE_REVIEW = 157;

// story
const TYPE_PIPE = 160;
const TYPE_STORY = 161;
const TYPE_STORY_TOPIC = 162;

// stream
const TYPE_CARD = 170;

// user
const TYPE_AVATAR = 180;
const TYPE_PRIVATE_KEY = 181;
const TYPE_PUBLIC_KEY = 182;
const TYPE_USER = 183;

// video
const TYPE_VIDEO = 190;

// os
const TYPE_ANDROID = 200;
const TYPE_CHROME_OS = 201;
const TYPE_FREEBSD = 203;
const TYPE_IPAD = 204;
const TYPE_IPHONE = 205;
const TYPE_LINUX = 206;
const TYPE_MAC = 207;
const TYPE_WINDOWS = 208;

// agent
const TYPE_CHROME = 210;
const TYPE_FIREFOX = 211;
const TYPE_IE = 212;
const TYPE_PIPECODE = 213;
const TYPE_PIPEDOT = 214;
const TYPE_SAFARI = 215;
const TYPE_PALEMOON = 216;


$default_conf["server_conf"] = [
	"auth_key" => "",
	"bug_enabled" => "0",
	"captcha_key" => "",
	"https_enabled" => "0",
	"https_redirect_enabled" => "0",
	"register_enabled" => "1",
	"server_name" => "example.com",
	"server_redirect_enabled" => "0",
	"server_slogan" => "News for nerds",
	"server_title" => "Example",
	"smtp_server" => "mail.example.com",
	"smtp_port" => "587",
	"smtp_address" => "mail@example.com",
	"smtp_username" => "mail@example.com",
	"smtp_password" => "",
	"time_zone" => "UTC",
	"twitter_enabled" => "0",
	"twitter_consumer_key" => "",
	"twitter_consumer_secret" => "",
	"twitter_oauth_token" => "",
	"twitter_oauth_secret" => "",
	"submit_enabled" => "1"
];

$default_conf["user_conf"] = [
	"admin" => "0",
	"avatar_id" => "0",
	"birthday" => "0",
	"developer" => "0",
	"display_name" => "",
	"editor" => "0",
	"email" => "",
	"expand_threshold" => "1",
	"gravatar_enabled" => "1",
	"gravatar_seen" => "0",
	"gravatar_sync" => "0",
	"hide_threshold" => "0",
	"inline_reply_enabled" => "0",
	"javascript_enabled" => "1",
	"joined" => "0",
	"large_text_enabled" => "0",
	"list_enabled" => "0",
	"password" => "",
	"salt" => "",
	"show_birthday_enabled" => "0",
	"show_email_enabled" => "0",
	"show_junk_enabled" => "0",
	"show_name_enabled" => "0",
	"story_image_style" => "3",
	"time_zone" => "UTC",
	"wysiwyg_enabled" => "1"
];



$db_table["article"] = array(
	array("name" => "article_id", "key" => true, "default" => 0),
	array("name" => "author_link"),
	array("name" => "author_name"),
	array("name" => "body"),
	array("name" => "description"),
	array("name" => "feed_html"),
	array("name" => "feed_id", "default" => 0),
	array("name" => "guid"),
	array("name" => "link"),
	array("name" => "publish_time", "default" => $now),
	array("name" => "redirect_link"),
	array("name" => "title"),
	array("name" => "thumb_id", "default" => 0)
);

$db_table["article_view"] = array(
	array("name" => "article_id", "default" => 0, "key" => true),
	array("name" => "zid", "key" => true),
	array("name" => "comments_clean", "default" => -1),
	array("name" => "comments_total", "default" => -1),
	array("name" => "time", "default" => 0),
	array("name" => "last_time", "default" => 0)
);

$db_table["avatar"] = array(
	array("name" => "avatar_id", "key" => true, "default" => 0),
	array("name" => "hash_64"),
	array("name" => "hash_128"),
	array("name" => "hash_256"),
	array("name" => "time", "default" => $now),
	array("name" => "zid")
);

$db_table["ban_ip"] = array(
	array("name" => "remote_ip", "key" => true),
	array("name" => "short_id", "default" => 0),
	array("name" => "time", "default" => $now),
	array("name" => "zid")
);

$db_table["bug"] = array(
	array("name" => "bug_id", "default" => 0, "key" => true),
	array("name" => "author_zid"),
	array("name" => "body"),
	array("name" => "closed", "default" => 0),
	array("name" => "closed_zid"),
	array("name" => "comments_clean", "default" => 0),
	array("name" => "comments_total", "default" => 0),
	array("name" => "priority"),
	array("name" => "publish_time", "default" => $now),
	array("name" => "title")
);

$db_table["bug_file"] = array(
	array("name" => "bug_file_id",  "default" => 0, "key" => true),
	array("name" => "bug_id", "default" => 0),
	array("name" => "hash"),
	array("name" => "name"),
	array("name" => "remote_ip"),
	array("name" => "server"),
	array("name" => "size", "default" => 0),
	array("name" => "time", "default" => $now),
	array("name" => "type"),
	array("name" => "zid")
);

$db_table["bug_label"] = array(
	array("name" => "label_id", "key" => true, "auto" => true),
	array("name" => "label_name"),
	array("name" => "label_tag"),
	array("name" => "background_color"),
	array("name" => "foreground_color"),
	array("name" => "reportable", "default" => 0)
);

$db_table["bug_labels"] = array(
	array("name" => "bug_id", "default" => 0),
	array("name" => "label_id", "default" => 0)
);

$db_table["bug_view"] = array(
	array("name" => "bug_id", "default" => 0, "key" => true),
	array("name" => "zid", "key" => true),
	array("name" => "time", "default" => 0),
	array("name" => "last_time", "default" => 0)
);

$db_table["cache"] = array(
	array("name" => "cache_id", "key" => true, "auto" => true),
	array("name" => "access_time", "default" => $now),
	array("name" => "create_time", "default" => $now),
	array("name" => "data_hash"),
	array("name" => "url"),
	array("name" => "url_hash")
);

$db_table["captcha"] = array(
	array("name" => "captcha_id", "auto" => true, "key" => true),
	array("name" => "question"),
	array("name" => "answer")
);

$db_table["captcha_challenge"] = array(
	array("name" => "remote_ip", "key" => true),
	array("name" => "captcha_id", "default" => 0)
);

$db_table["card"] = array(
	array("name" => "card_id", "default" => 0, "key" => true),
	array("name" => "body"),
	array("name" => "comments_clean", "default" => 0),
	array("name" => "comments_total", "default" => 0),
	array("name" => "edit_time", "default" => $now),
	array("name" => "image_id", "default" => 0),
	array("name" => "link_subject"),
	array("name" => "link_url"),
	array("name" => "photo_id", "default" => 0),
	array("name" => "publish_time", "default" => $now),
	array("name" => "zid"),
);

$db_table["card_edit"] = array(
	array("name" => "card_id", "default" => 0, "key" => true),
	array("name" => "edit_time", "key" => true, "default" => $now),
	array("name" => "body")
);

$db_table["card_tags"] = array(
	array("name" => "card_id", "default" => 0, "key" => true),
	array("name" => "tag", "key" => true)
);

$db_table["card_view"] = array(
	array("name" => "card_id", "default" => 0, "key" => true),
	array("name" => "zid", "key" => true),
	array("name" => "time", "default" => $now),
	array("name" => "last_time", "default" => 0)
);

$db_table["card_vote"] = array(
	array("name" => "card_id", "key" => true),
	array("name" => "zid", "key" => true),
	array("name" => "value", "default" => 0)
);

$db_table["comment"] = array(
	array("name" => "comment_id", "default" => 0, "key" => true),
	array("name" => "article_id", "default" => 0),
	array("name" => "body"),
	array("name" => "clean", "default" => 1),
	array("name" => "edit_time", "default" => $now),
	array("name" => "junk_status", "default" => 0),
	array("name" => "junk_time", "default" => 0),
	array("name" => "junk_zid"),
	array("name" => "parent_id", "default" => 0),
	array("name" => "publish_time", "default" => $now),
	array("name" => "remote_ip"),
	array("name" => "subject"),
	array("name" => "zid")
);

$db_table["comment_edit"] = array(
	array("name" => "comment_id", "key" => true),
	array("name" => "edit_time", "key" => true, "default" => $now),
	array("name" => "body"),
	array("name" => "subject")
);

$db_table["comment_vote"] = array(
	array("name" => "comment_id", "key" => true),
	array("name" => "zid", "key" => true),
	array("name" => "reason"),
	array("name" => "time", "default" => $now),
	array("name" => "value", "default" => 0)
);

$db_table["country"] = array(
	array("name" => "country_id", "key" => true, "auto" => true),
	array("name" => "country_code"),
	array("name" => "country_name")
);

$db_table["default_conf"] = array(
	array("name" => "conf", "key" => true),
	array("name" => "name", "key" => true),
	array("name" => "value")
);

$db_table["drive_data"] = array(
	array("name" => "hash", "key" => true),
	array("name" => "server_id", "default" => 0),
	array("name" => "size", "default" => 0)
);

$db_table["drive_file"] = array(
	array("name" => "file_id", "key" => true, "auto" => true),
	array("name" => "hash"),
	array("name" => "name"),
	array("name" => "parent_id", "default" => 0),
	array("name" => "time", "default" => $now),
	array("name" => "type", "default" => 1),
	array("name" => "zid")
);

$db_table["drive_link"] = array(
	array("name" => "hash", "key" => true),
	array("name" => "item_id", "key" => true, "default" => 0),
	array("name" => "type_id", "key" => true, "default" => 0),
	array("name" => "zid")
);

$db_table["email_challenge"] = array(
	array("name" => "code", "key" => true),
	array("name" => "email"),
	array("name" => "expires", "default" => $now + DAYS * 3),
	array("name" => "username")
);

$db_table["feed"] = array(
	array("name" => "feed_id", "key" => true),
	array("name" => "copyright"),
	array("name" => "description"),
	array("name" => "link"),
	array("name" => "slug"),
	array("name" => "time", "default" => $now),
	array("name" => "title"),
	array("name" => "topic_id", "default" => 0),
	array("name" => "uri")
);

$db_table["feed_topic"] = array(
	array("name" => "topic_id", "key" => true, "auto" => true),
	array("name" => "icon"),
	array("name" => "name"),
	array("name" => "slug")
);

$db_table["feed_user"] = array(
	array("name" => "zid", "key" => true),
	array("name" => "feed_id", "key" => true),
	array("name" => "col", "default" => 0),
	array("name" => "pos", "default" => 0)
);

$db_table["image"] = array(
	array("name" => "image_id", "key" => true),
	array("name" => "hash"),
	array("name" => "original_width", "default" => 0),
	array("name" => "original_height", "default" => 0),
	array("name" => "original_url"),
	array("name" => "parent_url"),
	array("name" => "server"),
	array("name" => "time", "default" => $now),
	array("name" => "zid")
);

$db_table["ip"] = array(
	array("name" => "ip_id", "key" => true, "auto" => true),
	array("name" => "address"),
	array("name" => "country_id", "default" => 0),
	array("name" => "latitude", "default" => 0),
	array("name" => "longitude", "default" => 0)
);

$db_table["journal"] = array(
	array("name" => "journal_id", "default" => 0, "key" => true),
	array("name" => "body"),
	array("name" => "comments_clean", "default" => 0),
	array("name" => "comments_total", "default" => 0),
	array("name" => "edit_time", "default" => $now),
	array("name" => "photo_id", "default" => 0),
	array("name" => "publish_time", "default" => 0),
	array("name" => "published", "default" => 0),
	array("name" => "slug"),
	array("name" => "title"),
	array("name" => "topic"),
	array("name" => "zid")
);

$db_table["journal_photo"] = array(
	array("name" => "journal_id", "key" => true, "default" => 0),
	array("name" => "photo_id", "key" => true, "default" => 0)
);

$db_table["journal_view"] = array(
	array("name" => "journal_id", "default" => 0, "key" => true),
	array("name" => "zid", "key" => true),
	array("name" => "time", "default" => 0),
	array("name" => "last_time", "default" => 0)
);

$db_table["login"] = array(
	array("name" => "zid", "key" => true),
	array("name" => "login_key", "key" => true),
	array("name" => "agent_id", "default" => 0),
	array("name" => "ip_id", "default" => 0),
	array("name" => "last_time", "default" => $now),
	array("name" => "login_time", "default" => $now),
	array("name" => "os_id", "default" => 0)
);

$db_table["mail"] = array(
	array("name" => "mail_id", "auto" => true, "key" => true),
	array("name" => "body"),
	array("name" => "in_reply_to"),
	array("name" => "location"),
	array("name" => "mail_from"),
	array("name" => "message_id"),
	array("name" => "rcpt_to"),
	array("name" => "received_time", "default" => $now),
	array("name" => "reply_to"),
	array("name" => "size", "default" => 0),
	array("name" => "subject"),
	array("name" => "zid")
);

$db_table["notification"] = array(
	array("name" => "notification_id", "auto" => true, "key" => true),
	array("name" => "item_id", "default" => 0),
	array("name" => "parent_id", "default" => 0),
	array("name" => "time", "default" => $now),
	array("name" => "type_id", "default" => 0),
	array("name" => "zid")
);

$db_table["page"] = array(
	array("name" => "slug", "key" => true),
	array("name" => "title"),
	array("name" => "body")
);

$db_table["photo"] = array(
	array("name" => "photo_id", "default" => 0, "key" => true),
	array("name" => "aspect_width", "default" => 0),
	array("name" => "aspect_height", "default" => 0),
	array("name" => "has_medium", "default" => 0),
	array("name" => "has_large", "default" => 0),
	array("name" => "hash"),
	array("name" => "original_name", "default" => 0),
	array("name" => "original_width", "default" => 0),
	array("name" => "original_height", "default" => 0),
	array("name" => "server"),
	array("name" => "size", "default" => 0),
	array("name" => "time", "default" => $now),
	array("name" => "zid")
);

$db_table["pipe"] = array(
	array("name" => "pipe_id", "default" => 0, "key" => true),
	array("name" => "author_zid"),
	array("name" => "body"),
	array("name" => "closed", "default" => 0),
	array("name" => "comments_clean", "default" => 0),
	array("name" => "comments_total", "default" => 0),
	array("name" => "edit_zid"),
	//array("name" => "icon"),
	array("name" => "keywords"),
	array("name" => "reason"),
	array("name" => "slug"),
	array("name" => "topic_id", "default" => 0),
	array("name" => "time", "default" => $now),
	array("name" => "title")
);

$db_table["pipe_view"] = array(
	array("name" => "pipe_id", "default" => 0, "key" => true),
	array("name" => "zid", "key" => true),
	array("name" => "time", "default" => 0),
	array("name" => "last_time", "default" => 0)
);

$db_table["pipe_vote"] = array(
	array("name" => "pipe_id", "key" => true),
	array("name" => "zid", "key" => true),
	array("name" => "time", "default" => $now),
	array("name" => "value", "default" => 0)
);

$db_table["poll_answer"] = array(
	array("name" => "answer_id", "key" => true),
	array("name" => "poll_id"),
	array("name" => "answer"),
	array("name" => "position", "default" => 0)
);

$db_table["poll"] = array(
	array("name" => "poll_id", "default" => 0, "key" => true),
	array("name" => "comments_clean", "default" => 0),
	array("name" => "comments_total", "default" => 0),
	array("name" => "promoted", "default" => 0),
	array("name" => "publish_time", "default" => $now),
	array("name" => "question"),
	array("name" => "slug"),
	array("name" => "type_id", "default" => 0),
	array("name" => "zid")
);

$db_table["poll_view"] = array(
	array("name" => "poll_id", "default" => 0, "key" => true),
	array("name" => "zid", "key" => true),
	array("name" => "time", "default" => 0),
	array("name" => "last_time", "default" => 0)
);

$db_table["poll_vote"] = array(
	array("name" => "poll_id"),
	array("name" => "zid"),
	array("name" => "answer_id"),
	array("name" => "time", "default" => $now),
	array("name" => "points", "default" => 0)
);

$db_table["reader_topic"] = array(
	array("name" => "topic_id", "auto" => true, "key" => true),
	array("name" => "icon"),
	array("name" => "name"),
	array("name" => "slug"),
	array("name" => "zid")
);

$db_table["reader_user"] = array(
	array("name" => "zid", "key" => true),
	array("name" => "feed_id", "key" => true),
	array("name" => "name"),
	array("name" => "slug"),
	array("name" => "topic_id", "default" => 0)
);

$db_table["server_conf"] = array(
	array("name" => "name"),
	array("name" => "value")
);

$db_table["short"] = array(
	array("name" => "short_id", "auto" => true, "key" => true),
	array("name" => "type_id")
);

$db_table["short_view"] = array(
	array("name" => "view_id", "auto" => true, "key" => true),
	array("name" => "short_id"),
	array("name" => "remote_ip"),
	array("name" => "time", "default" => $now),
	array("name" => "agent"),
	array("name" => "referer"),
	array("name" => "zid")
);

$db_table["story"] = array(
	array("name" => "story_id", "default" => 0, "key" => true),
	array("name" => "author_zid"),
	array("name" => "body"),
	array("name" => "comments_clean", "default" => 0),
	array("name" => "comments_total", "default" => 0),
	array("name" => "edit_time", "default" => $now),
	array("name" => "edit_zid"),
	array("name" => "icon"),
	array("name" => "image_id", "default" => 0),
	array("name" => "keywords"),
	array("name" => "pipe_id"),
	array("name" => "publish_time", "default" => $now),
	array("name" => "slug"),
	array("name" => "topic_id", "default" => 0),
	array("name" => "title"),
	array("name" => "tweet_id", "default" => 0)
);

$db_table["story_edit"] = array(
	array("name" => "story_id", "key" => true),
	array("name" => "edit_time", "key" => true, "default" => $now),
	array("name" => "body"),
	array("name" => "edit_zid"),
	array("name" => "icon"),
	array("name" => "image_id", "default" => 0),
	array("name" => "slug"),
	array("name" => "topic_id", "default" => 0),
	array("name" => "title")
);

$db_table["story_view"] = array(
	array("name" => "story_id", "default" => 0, "key" => true),
	array("name" => "zid", "key" => true),
	array("name" => "time", "default" => $now),
	array("name" => "last_time", "default" => 0)
);

$db_table["tag"] = array(
	array("name" => "tag_id", "auto" => true, "key" => true),
	array("name" => "tag")
);

$db_table["thumb"] = array(
	array("name" => "thumb_id", "key" => true),
	array("name" => "hash"),
	array("name" => "low_res", "default" => 0),
	array("name" => "time", "default" => $now)
);

$db_table["tmp_image"] = array(
	array("name" => "tmp_image_id", "auto" => true, "key" => true),
	array("name" => "hash"),
	array("name" => "original_width", "default" => 0),
	array("name" => "original_height", "default" => 0),
	array("name" => "original_url"),
	array("name" => "parent_url"),
	array("name" => "server"),
	array("name" => "time", "default" => $now),
	array("name" => "zid")
);

$db_table["topic"] = array(
	array("name" => "topic_id", "auto" => true, "key" => true),
	array("name" => "icon"),
	array("name" => "promoted", "default" => 0),
	array("name" => "slug"),
	array("name" => "topic")
);

$db_table["user_conf"] = array(
	array("name" => "zid", "key" => true),
	array("name" => "name"),
	array("name" => "value")
);
