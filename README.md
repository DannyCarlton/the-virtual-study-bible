# The Virtual Study Bible Wordpress Plugin

The purpose of this project is to provide a full Study Bible as a plugin for a Wordpress site. While there exists plenty of Bible plugins, the vast majority pull the content from outside sources. This plugin will instead allow the site owner to install all the data necessary to their own site. 

While I am a coder, I am also a designer, and my goal, with all my sites, has been to provide a design that is conducive to the function of the site itself. I've always strived, therefore to make all my Bible sites as visually pleasing as I can, studying the designs of as many popular Bible publisher and utilizing those ideas. Form should follow function, but too many Bible sites have abandoned form altogether.

### My Coding Style 

I like being able to tell arrays apart from simple variables, so I always used camelCase for arrays and snake_case for regular variables. If it's just one word then the regular variable is in all lowercase and at least one letter in the array name is Capitalized. However, some of my coding I reuse, and sometimes I will copy older code, before I began that practice, which won't use that naming convention. However, I've tried to make all the coding consistent.

Variables that function as a sort of pseudo-constant (for example $_mysql) that more or less remain unchanged throughout the script, I generally begin with a single underscore. And more recently I began formatting the variable names for objects with a capital letter and two underscores at the beginning (for example $__moduleData).

Also, it's easier for me to see the logic in a function or statement if I spread it out a bit vertically. This applies to both PHP and JavaScript. For example where typically coders would do this... 
<pre>
if($some_choice){
	$then_do_this;
}
</pre>

	I would structure it... 

<pre>
if($some_choice)
	{
	$then_do_this;
	} 
</pre>

	...and even in JavaScript, I repeat the pattern even for parentheses (most of the time) so you'd see... 

<pre>			
$("#some-element-trigger").on
	(
	"click", function(e)
		{
		$.ajax
			(
				{
				type: "GET",
				url: nonce_url,
				data: {keyword:keyword},
				success: function(data)
					{
					$("#some-element-content").html(data);
					}
				}
			);
		}
	);
</pre>

Yes, not an efficient use of vertical space, but, to me, much easier to read when I'm scanning through thousands of lines of code.