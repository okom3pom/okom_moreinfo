{*
*  
* 	http://okom3pom.com
*	Module Ask question on product page for Prestashop
*
*	Released under the GNU General Public License
*
*	Author Okom3pom.com -> Thomas Roux
*	Version 1.4 - 07/08/2014
* 
*}
<div style="display:none">
	<div id="moreinfo_form">
			<h2>{l s='A question ?' mod='okom_moreinfo'}</h2>
			<div class="product clearfix">
				<img src="{$link->getImageLink($product->link_rewrite, (int)$cover['id_image'], 'medium_default')}" alt="{$product->name|escape:html:'UTF-8'}" />
				<div class="product_desc">
					<p class="product_name"><strong>{$product->name}</strong></p>
					{if $product->show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}<p class="price_container"><span class="price">{if !$priceDisplay}{convertPrice price=$product->getPrice()}{else}{convertPrice price=$product->getPrice(false)}{/if}</span></p>{else}<div style="height:21px;"></div>{/if}
				</div>
			</div>
			
			<div class="moreinfo_form_content">
				<div class="moreinfo_waiting"><img src="{$img_ps_dir}loadingAnimation.gif" alt="{l s='Please wait' mod='okom_moreinfo'}" /></div>
				<div id="moreinfo_form_error"></div>
				<form action="{$link->getModuleLink('okom_moreinfo')}" method="post" id="moreinfo_frm">
					<div class="moreinfo_form_container">
						<div class="intro_form">
						
						
						{if $phone_number}{l s='You can also contact us by phone'} <br/><span class="phone"><i class="icon-phone-sign"></i> {$phone_number}</span><br>{/if}
						{$message}
						{if $schedule}{$schedule}{/if}
						
						</div>
						<p class="moreinfo_form">
							<label for="moreinfo_firstname">{l s='Firstname' mod='okom_moreinfo'} <sup class="required">*</sup> :</label>
							<input id="moreinfo_firstname" name="moreinfo_firstname" type="text" value=""/>
						</p>

						<p class="moreinfo_form">
							<label for="moreinfo_email">{l s='Email' mod='okom_moreinfo'} <sup class="required">*</sup> :</label>
							<input id="moreinfo_email" name="moreinfo_email" type="text" value=""/>
						</p>

						<p class="moreinfo_form">
							<label for="moreinfo_question">{l s='Comment' mod='okom_moreinfo'} :</label>
							<textarea cols="80" rows="3" name="moreinfo_question" id="moreinfo_question"></textarea>
						</p>
						
						<p class="moreinfo_form">					
							{$secure_image}							
						</p>
						
						
						<p class="txt_required"><sup class="required">*</sup> {l s='Required fields' mod='okom_moreinfo'}</p>
					</div>
					<p class="submit">
						<input id="moreinfo_product" name="moreinfo_product" type="hidden" value="{$product->id}" />
						<input type="button" class="moreinfo_cancel" value="{l s='Cancel' mod='okom_moreinfo'}" />&nbsp;{l s='or' mod='okom_moreinfo'}&nbsp;
						<input id="moreinfo_submit" class="button" name="moreinfo_submit" type="submit" value="{l s='Send' mod='okom_moreinfo'}" />
					</p>
				</div>
			</form>
	</div>
</div>
