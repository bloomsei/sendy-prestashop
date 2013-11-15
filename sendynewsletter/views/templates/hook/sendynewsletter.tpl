{*
* The MIT License (MIT)
* 
* Copyright (c) 2013 Iztok Svetik
* 
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
* 
* The above copyright notice and this permission notice shall be included in all
* copies or substantial portions of the Software.
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*
* -----------------------------------------------------------------------------
* @author   Iztok Svetik
* @website  http://www.isd.si
* @github   https://github.com/iztoksvetik
*}

<!-- Sendy Newsletter module-->
<div id="sendy_newsletter" class="block">
	<h4 class="title_block">{l s='Newsletter' mod='sendynewsletter'}</h4>
	<div class="block_content">
		<form id="sendynewsletter_form" action="{$sendynews.url}/subscribe" method="post">
			<p>
				<input type="hidden" id="sendynewsletter_list" name="list" value="{$sendynews.list}" />
				<input type="hidden" id="sendynewsletter_ip" name="{if $sendynews.ip == 1}{$sendynews.ipfield}{else}ip{/if}" value="{$sendynews.ipval}" />
				{if $sendynews.name == 1}
					<input id="sendynewsletter_name" type="text" name="name" placeholder="{l s='Your name' mod='sendynewsletter'}" {if $sendynews.namereq == 1}data-req="true" required{/if}/>
				{/if}

				<input id="sendynewsletter_email" type="text" name="email" size="18" placeholder="{l s='Your email address' mod='sendynewsletter'}" required/>
				<input type="submit" value="ok" class="button_mini" name="submitNewsletter" />
			</p>
		</form>
		<p id="sn_error" class="sn_warning">{l s='There was an error please try again.' mod='sendynewsletter'}</p>
		<p id="sn_email" class="sn_warning">{l s='Invalid email address.' mod='sendynewsletter'}</p>
		<p id="sn_subscribed" class="sn_warning">{l s='Already subscribed.' mod='sendynewsletter'}</p>
		<p id="sn_name" class="sn_warning">{l s='Please enter your name.' mod='sendynewsletter'}</p>
		<p class="sn_success">{l s='You were subscribed to our newsletter.' mod='sendynewsletter'}</p>
	</div>
</div>
<!-- /Sendy Newsletter module-->
