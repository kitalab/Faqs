<?php
/**
 * FaqQuestionOrders edit
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->script('/faqs/js/faqs.js');

$faqQuestions = NetCommonsAppController::camelizeKeyRecursive($this->data['FaqQuestions']);
$faqQuestionsMap = array_flip(array_keys(Hash::combine($faqQuestions, '{n}.faqQuestion.key')));
?>

<div ng-controller="FaqQuestionOrders" class="nc-content-list"
	 ng-init="initialize(<?php echo h(json_encode(['faqQuestions' => $faqQuestions, 'faqQuestionsMap' => $faqQuestionsMap])); ?>)">

	<article>
		<h1>
			<small><?php echo h($faq['name']); ?></small>
		</h1>

		<?php echo $this->Form->create('FaqQuestionOrder', array('novalidate' => true)); ?>
			<?php foreach ($faqQuestionsMap as $key => $value) : ?>
				<?php echo $this->Form->hidden('FaqQuestions.' . $value . '.FaqQuestionOrder.id'); ?>
				<?php echo $this->Form->hidden('FaqQuestions.' . $value . '.FaqQuestionOrder.faq_key'); ?>
				<?php echo $this->Form->hidden('FaqQuestions.' . $value . '.FaqQuestionOrder.faq_question_key'); ?>
				<?php $this->Form->unlockField('FaqQuestions.' . $value . '.FaqQuestionOrder.weight'); ?>
			<?php endforeach; ?>

			<?php echo $this->Form->hidden('Block.id'); ?>
			<?php echo $this->Form->hidden('Block.key'); ?>
			<?php echo $this->Form->hidden('Faq.id'); ?>
			<?php echo $this->Form->hidden('Faq.key'); ?>

			<div ng-hide="faqQuestions.length">
				<p><?php echo __d('net_commons', 'Not found.'); ?></p>
			</div>

			<table class="table table-condensed" ng-show="faqQuestions.length">
				<thead>
					<tr>
						<th></th>
						<th>
							<?php echo $this->Paginator->sort('FaqQuestion.question', __d('faqs', 'Question')); ?>
						</th>
						<th>
							<?php echo $this->Paginator->sort('CategoryOrder.weight', __d('categories', 'Category')); ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="q in faqQuestions track by $index">
						<td>
							<button type="button" class="btn btn-default btn-sm"
									ng-click="move('up', $index)" ng-disabled="$first">
								<span class="glyphicon glyphicon-arrow-up"></span>
							</button>

							<button type="button" class="btn btn-default btn-sm"
									ng-click="move('down', $index)" ng-disabled="$last">
								<span class="glyphicon glyphicon-arrow-down"></span>
							</button>

							<input type="hidden" name="data[FaqQuestions][{{getIndex(q.faqQuestion.key)}}][FaqQuestionOrder][weight]" ng-value="{{$index + 1}}">
						</td>
						<td>
							{{q.faqQuestion.question}}
						</td>
						<td>
							{{q.category.name}}
						</td>
					</tr>
				</tbody>
			</table>

			<div class="text-center">
				<?php echo $this->Button->cancelAndSave(__d('net_commons', 'Cancel'), __d('net_commons', 'OK')); ?>
			</div>

		<?php echo $this->Form->end(); ?>
	</article>
</div>
