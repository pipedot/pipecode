//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
//
// This file is part of Pipecode.
//
// Pipecode is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Pipecode is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Pipecode.  If not, see <http://www.gnu.org/licenses/>.
//

var comments;
var reasons = new Array("Normal", "Offtopic", "Flamebait", "Troll", "Redundant", "Insightful", "Interesting", "Informative", "Funny", "Overrated", "Underrated");


// jquery checkbox toggle on table row click
$(document).ready(function() {
	$('.hover').click(function(event) {
		if (event.target.type !== 'checkbox') {
			$(':checkbox', this).trigger('click');
		}
	});
});


function moderate(e, cid)
{
	data = "rid=" + e.value;
	$.post("/moderate/" + cid, data, function(data) {
		if (data.indexOf("error:") != -1) {
			alert(data);
		} else {
			a = data.split(" ");
			cid = a[0];
			if (a.length == 2) {
				score = a[1].trim();
			} else {
				score = a[1] + " " + a[2].trim();
			}
			$("#score_" + cid).html(score);
		}
	});
}


function update_hide_slider()
{
	hide_value = $("#slider_hide").prop("value");
	$("#label_hide").html(hide_value);
	if (expand_value < hide_value) {
		expand_value = hide_value;
		$("#slider_expand").prop("value", expand_value);
		$("#label_expand").html(expand_value);
	}
	render_page();
}


function update_expand_slider()
{
	expand_value = $("#slider_expand").prop("value");
	$("#label_expand").html(expand_value);
	if (hide_value > expand_value) {
		hide_value = expand_value;
		$("#slider_hide").prop("value", hide_value);
		$("#label_hide").html(hide_value);
	}
	render_page();
}


function get_comments(sid, pid, qid)
{
	var uri;

	if (sid > 0) {
		uri = "/story/" + sid + "/comments";
	} else if (pid > 0) {
		uri = "/pipe/" + pid + "/comments";
	} else if (qid > 0) {
		uri = "/poll/" + qid + "/comments";
	}

	$.getJSON(uri, function(json) {
		comments = json;
		render_page();
	});
}


function render_page()
{
	s = render(comments);
	$('#comment_box').html(s);
}


function show_comment(cid)
{
	$('#collapse_' + cid).hide();
	$('#subject_' + cid).show();
	$('#subtitle_' + cid).show();
	$('#body_' + cid).show();
}


function render(c)
{
	var i;
	var s = "";
	var d;
	var t;
	var hide = false;
	var collapse = false;
	var by;
	var seen;

	if (c === undefined) {
		return "";
	}

	if (c.zid !== undefined) {
		d = new Date(c.time * 1000);
		t = d.getFullYear() + "-" + ("0" + (d.getMonth() + 1)).slice(-2) + "-" + ("0" + d.getDate()).slice(-2) + " " + ("0" + d.getHours()).slice(-2) + ":" + ("0" + d.getMinutes()).slice(-2);

		if (c.score < hide_value) {
			hide = true;
		} else {
			if (c.score < expand_value) {
				collapse = true;
			}
		}
		user_page = "http://" + c.zid.replace("@", ".") + "/";
		if (c.zid == "") {
			by = "Anonymous Coward";
		} else {
			by = "<a href=\"http://" + c.zid.replace("@", ".") + "/\">" + c.zid + "</a>";
		}

		if (hide) {
			s += "<div>";
		} else {
			if (c.time > last_seen) {
				seen = "h1";
			} else {
				seen = "h2";
			}
			s += "<article class=\"comment\">";
			if (collapse) {
				s += "<h4 id=\"collapse_" + c.cid + "\" onclick=\"show_comment(" + c.cid + ")\"><b>" + c.subject + ":</b> " + c.comment + "</h4>";

				s += "<" + seen + " id=\"subject_" + c.cid + "\" style=\"display: none\">" + c.subject + " (Score: <span id=\"score_" + c.cid + "\">" + c.score + "</span>)</" + seen + ">";
				s += "<h3 id=\"subtitle_" + c.cid + "\" class=\"comment_subtitle\" style=\"display: none\">by " + by + " on " + t + " (<a href=\"/comment/" + c.cid + "\">#" + c.cid + "</a>)</h3>";
				s += "<div class=\"comment_body\">";
				s += "<div id=\"body_" + c.cid + "\" class=\"comment_content\" style=\"display: none\">";
			} else {
				s += "<" + seen + " id=\"subject_" + c.cid + "\">" + c.subject + " (Score: <span id=\"score_" + c.cid + "\">" + c.score + "</span>)</" + seen + ">";
				s += "<h3 id=\"subtitle_" + c.cid + "\">by " + by + " on " + t + " (<a href=\"/comment/" + c.cid + "\">#" + c.cid + "</a>)</h3>";
				s += "<div class=\"comment_body\">";
				s += "<div id=\"body_" + c.cid + "\" class=\"comment_content\">";
			}
			s += c.comment;
			s += "<footer><a href=\"/post?cid=" + c.cid + "\">Reply</a>";
			if (c.rid >= 0 && c.zid != auth_zid) {
				s += "<select name=\"s_" + c.cid + "\" onchange=\"moderate(this, " + c.cid + ")\">";

				for (i = 0; i < reasons.length; i++) {
					if (i == c.rid) {
						s += "<option value=\"" + i + "\" selected=\"selected\">" + reasons[i] + "</option>";
					} else {
						s += "<option value=\"" + i + "\">" + reasons[i] + "</option>";
					}
				}
				s += "</select>";
			}
			s += "</footer>";
			s += "</div>";
		}
	}

	for (i = 0; i < c.reply.length; i++) {
		//s += render_comment(json.reply[i]);
		s += render(c.reply[i]);
	}

	if (c.zid !== undefined) {
		//if (collapse) {
		//	s += "</div>";
		//}
		s += "</div>";
		s += "</article>";
	}

	return s;
}
