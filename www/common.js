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
var protocol = get_protocol();
var server_name = get_server_name();


// jquery checkbox toggle on table row click
$(document).ready(function() {
	$('.hover').click(function(event) {
		if (event.target.type !== 'checkbox') {
			$(':checkbox', this).trigger('click');
		}
	});
});


function get_protocol()
{
	var a;

	a = document.URL.split(":");

	return a[0];
}


function get_server_name()
{
	var a;

	a = document.URL.split("/");
	a = a[2].split(".");

	if (a.length == 2) {
		return a[0] + "." + a[1];
	} else {
		return a[1] + "." + a[2];
	}
}


function moderate(e, comment_id)
{
	var a;
	var score;
	var reason;
	var s;

	data = "reason=" + e.value;
	$.post("/moderate/" + comment_id, data, function(data) {
		if (data.indexOf("error:") != -1) {
			alert(data);
		} else {
			a = data.split(" ");
			comment_id = a[0];
			score = a[1].trim();
			reason = a[2].trim();
			s = score;
			if (reason != "") {
				s += ", " + reason;
			}
			$("#score_" + comment_id).html(s);
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


function get_comments(type, root_id)
{
	var uri;

	uri = "/" + type + "/" + root_id + "/comments";
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


function show_comment(comment_id)
{
	$('#collapse_' + comment_id).hide();
	$('#subject_' + comment_id).show();
	$('#subtitle_' + comment_id).show();
	$('#body_' + comment_id).show();
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
	var score;

	if (c === undefined) {
		return "";
	}

	if (c.zid !== undefined) {
		d = new Date(c.time * 1000);
		t = d.getFullYear() + "-" + ("0" + (d.getMonth() + 1)).slice(-2) + "-" + ("0" + d.getDate()).slice(-2) + " " + ("0" + d.getHours()).slice(-2) + ":" + ("0" + d.getMinutes()).slice(-2);
		score = c.score;
		if (c.reason != "") {
			score += ", " + c.reason;
		}

		if (c.score < hide_value) {
			hide = true;
		} else {
			if (c.score < expand_value) {
				collapse = true;
			}
		}
		//console.log("comment_id [" + c.comment_id + "] score [" + c.score + "] hide [" + hide + "] collapse [" + collapse + "] hide_value [" + hide_value + "] expand_value [" + expand_value + "]");
		user_page = protocol + "://" + c.zid.replace("@", ".") + "/";
		if (c.zid == "") {
			by = "Anonymous Coward";
		} else {
			by = "<a href=\"" + protocol + "://" + c.zid.replace("@", ".") + "/\">" + c.zid + "</a>";
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
				s += "<h4 id=\"collapse_" + c.comment_id + "\" onclick=\"show_comment('" + c.comment_id + "')\"><b>" + c.subject + ":</b> " + c.body + "</h4>";

				s += "<" + seen + " id=\"subject_" + c.comment_id + "\" style=\"display: none\">" + c.subject + " (Score: <span id=\"score_" + c.comment_id + "\">" + score + "</span>)</" + seen + ">";
				s += "<h3 id=\"subtitle_" + c.comment_id + "\" class=\"comment_subtitle\" style=\"display: none\">by " + by + " on " + t + " (<a href=\"" + protocol + "://" + server_name + "/" + c.short + "\">#" + c.short + "</a>)</h3>";
				s += "<div class=\"comment_outline\">";
				s += "<div id=\"body_" + c.comment_id + "\" style=\"display: none\">";
			} else {
				s += "<" + seen + " id=\"subject_" + c.comment_id + "\">" + c.subject + " (Score: <span id=\"score_" + c.comment_id + "\">" + score + "</span>)</" + seen + ">";
				s += "<h3 id=\"subtitle_" + c.comment_id + "\">by " + by + " on " + t + " (<a href=\"" + protocol + "://" + server_name + "/" + c.short + "\">#" + c.short + "</a>)</h3>";
				s += "<div class=\"comment_outline\">";
				s += "<div id=\"body_" + c.comment_id + "\">";
			}
			s += "<div class=\"comment_body\">" + c.body + "</div>";
			s += "<footer><div><a rel=\"nofollow\" href=\"" + protocol + "://" + server_name + "/post?comment_id=" + c.comment_id + "\">Reply</a>";
			if (c.zid != auth_zid) {
				s += "<select name=\"s_" + c.comment_id + "\" onchange=\"moderate(this, '" + c.comment_id + "')\">";

				for (i = 0; i < reasons.length; i++) {
					if (reasons[i] == c.vote) {
						//s += "<option value=\"" + i + "\" selected=\"selected\">" + reasons[i] + "</option>";
						s += "<option selected=\"selected\">" + reasons[i] + "</option>";
					} else {
						//s += "<option value=\"" + i + "\">" + reasons[i] + "</option>";
						s += "<option>" + reasons[i] + "</option>";
					}
				}
				s += "</select>";
			} else {
				s += "</div>";
				s += "<div class=\"right\">";
				s += "<a class=\"icon_16 notepad_16\" href=\"" + protocol + "://" + server_name + "/comment/" + c.short + "/edit\">Edit</a>";
			}
			s += "</div>";
			s += "</footer>";
			s += "</div>";
		}
	}

	for (i = 0; i < c.reply.length; i++) {
		s += render(c.reply[i]);
	}

	if (c.zid !== undefined) {
		s += "</div>";
		s += "</article>";
	}

	return s;
}
