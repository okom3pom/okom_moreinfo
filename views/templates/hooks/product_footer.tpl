

<section class="page-propduct-box">



	<span class="page-product-heading">{l s='Question from customer' mod='okom_moreinfo'}</span>	
	
	
	<div id="qnaTab">
		
		<a class="iframe-okom-moreinfo" rel="nofollow" href="{$link->getModuleLink('okom_moreinfo')}?id_product={$id_product}&amp;content_only=1&amp;n=1">{l s='Une question sur cet article ?' mod='okom_moreinfo'}</a>
		{if isset($questions) && $questions}
			<div class="qna-answers">
				<ul>
					{foreach from=$questions item=question}
						<li>
								<span class="name">
									{if $question.date_add !="0000-00-00"}
										<em>{l s='Question Ask on' mod='okom_moreinfo'} {dateFormat date=$question.date_add}</em>
									{/if}
								</span>
								<span class="question">{$question.question}</span>
								<span class="answer">
									<strong>{l s='Answer:' mod='okom_moreinfo'}</strong>	<br/>
									{$question.answer}
								</span>

							<div style="clear:both"></div>	 
						</li>
					{/foreach}
				</ul>
			</div>
		{/if}		
	</div>

</section>

