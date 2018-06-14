<div id="moreinfo_form">
			<h2>{l s='A question ?' mod='okom_moreinfo'}</h2>
			<div class="product clearfix">
				<img class="img-responsive" src="{$link->getImageLink($product->link_rewrite, (int)$cover['id_image'], 'medium_default')}" alt="{$product->name|escape:html:'UTF-8'}" />
				<div class="product_desc">
					<p class="product_name"><strong>{$product->name}</strong></p>
					{$product->description_short}
					{if $product->show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}<p class="price_container"><span class="price">{if !$priceDisplay}{convertPrice price=$product->getPrice()}{else}{convertPrice price=$product->getPrice(false)}{/if}</span></p>{else}<div style="height:21px;"></div>{/if}
				</div>
			</div>
			
			<div class="moreinfo_form_content">
				<div class="moreinfo_waiting"><img src="{$img_ps_dir}loadingAnimation.gif" alt="{l s='Please wait' mod='okom_moreinfo'}" /></div>
				<div id="moreinfo_form_error"></div>
				<form action="{$link->getModuleLink('okom_moreinfo')}"  id="moreinfo_frm"  method="post" class="contact-form-box" enctype="multipart/form-data">
					<div class="moreinfo_form_container">
						<div class="intro_form">
						
						{if $faq_link}<a href="{$faq_link}">{l s='Thank you for reading our faq before you ask us a question' mod='okom_moreinfo'} </a><br/>{/if} 
						{if $phone_number}{l s='You can also contact us by phone' mod='okom_moreinfo'} <br/><span class="phone"><i class="icon-phone-sign"></i> {$phone_number}</span><br>{/if}
						
						{if $schedule}{$schedule}{/if}
						
						{$message}
						</div>
						<p class="form-group">
							<label for="moreinfo_firstname">{l s='Firstname' mod='okom_moreinfo'} <sup class="required">*</sup> :</label>
							<input id="moreinfo_firstname" class="form-control grey validate" data-validate="isGenericName" name="moreinfo_firstname" type="text" value="{if $customer->firstname}{$customer->firstname}{/if}"/>
						</p>

						 <p class="form-group">
							<label for="moreinfo_email">{l s='Email' mod='okom_moreinfo'} <sup class="required">*</sup> :</label>
							<input class="form-control grey validate" id="moreinfo_email" name="moreinfo_email" data-validate="isEmail" value="{if $customer->email}{$customer->email}{/if}" type="text">
						</p>

						 <p class="form-group">
							<label for="moreinfo_question">{l s='Question' mod='okom_moreinfo'} <sup class="required">*</sup> :</label>
							<textarea class="form-control validate" data-validate="isMessage" name="moreinfo_question" id="moreinfo_question"></textarea>
						</p>


						
						{*
						{if $fileupload == 1}
						 <p class="form-group">
                        <label for="fileUpload">{l s='Attach File'}</label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
                        <input type="file" name="fileUpload" id="fileUpload" class="form-control" />
						</p>
						{/if}
						*}
						
						<p class="form-group">					
							{$secure_image}							
						</p>
						{*
						<p class="checkbox">
							<div class="col-xs-1">
                            	<br/><input type="checkbox" name="consent" id="consent" value="1" data-href="https://www.esprit-equitation.com/content/3-conditions-generales-de-ventes?content_only=1">
                            </div>

							<div class="col-xs-11">
								<label for="consent">En soumettant ce formulaire, j'accepte que les informations saisies dans ce formulaire soient utilisées, exploitées ou traitées pour permettre de me recontacter dans le cadre de la relation commerciale qui découle de cette demande.</label>
							</div>
                		</p> *}
											
						<p class="txt_required"><sup class="required">*</sup> {l s='Required fields' mod='okom_moreinfo'}</p>

						{hook h='displayGDPRConsent'  id_module=$id_module}

					</div>
					
					<p class="submit">
						<input id="moreinfo_product" name="moreinfo_product" type="hidden" value="{$product->id}" />
						<input id="moreinfo_product" name="sendEmail" type="hidden" value="" />
						<button id="moreinfo_submit" class="btn button button-small" type="submit">
							<span>{l s='Send' mod='okom_moreinfo'}</span>
						</button>
					</p>
				</div>
			</form>
</div>

