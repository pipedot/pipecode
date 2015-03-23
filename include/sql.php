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
	array("name" => "cache_id", "key" => true),
	array("name" => "hash"),
	array("name" => "time", "default" => $now),
	array("name" => "url")
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
	array("name" => "body"),
	array("name" => "edit_time", "default" => $now),
	array("name" => "junk_status", "default" => 0),
	array("name" => "junk_time", "default" => 0),
	array("name" => "junk_zid"),
	array("name" => "parent_id", "default" => 0),
	array("name" => "publish_time", "default" => $now),
	array("name" => "remote_ip"),
	array("name" => "root_id", "default" => 0),
	array("name" => "subject"),
	array("name" => "type"),
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

$db_table["email_challenge"] = array(
	array("name" => "challenge", "key" => true),
	array("name" => "username"),
	array("name" => "email"),
	array("name" => "expires", "default" => $now + 86400 * 3)
);

$db_table["feed"] = array(
	array("name" => "feed_id", "key" => true),
	array("name" => "copyright"),
	array("name" => "description"),
	array("name" => "link"),
	array("name" => "slug"),
	array("name" => "time", "default" => $now),
	array("name" => "title"),
	array("name" => "uri")
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

$db_table["journal"] = array(
	array("name" => "journal_id", "default" => 0, "key" => true),
	array("name" => "body"),
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
	array("name" => "edit_zid"),
	array("name" => "icon"),
	array("name" => "reason"),
	array("name" => "slug"),
	array("name" => "tid", "default" => 0),
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

$db_table["server_conf"] = array(
	array("name" => "name"),
	array("name" => "value")
);

$db_table["short"] = array(
	array("name" => "short_id", "auto" => true, "key" => true),
	array("name" => "type")
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
	array("name" => "edit_time", "default" => $now),
	array("name" => "edit_zid"),
	array("name" => "icon"),
	array("name" => "image_id", "default" => 0),
	array("name" => "pipe_id"),
	array("name" => "publish_time", "default" => $now),
	array("name" => "slug"),
	array("name" => "tid", "default" => 0),
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
	array("name" => "tid", "default" => 0),
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
	array("name" => "tid", "auto" => true, "key" => true),
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

