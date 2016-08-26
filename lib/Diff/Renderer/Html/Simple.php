<?php
// wangyanxiang@baixing.com


require_once dirname(__FILE__).'/Array.php';

class Diff_Renderer_Html_Simple extends Diff_Renderer_Html_Array
{
	/**
	 * Render a and return diff with changes between the two sequences
	 * displayed inline (under each other)
	 *
	 * @return string The generated inline diff.
	 */
	public function render()
	{
		$changes = parent::render();
		$html = '';
		if(empty($changes)) {
			return $html;
		}

		$html .= '<table class="Differences DifferencesInline">';
		$html .= '<thead>';
		$html .= '</thead>';
		foreach($changes as $i => $blocks) {
			// If this is a separate block, we're condensing code so output ...,
			// indicating a significant portion of the code has been collapsed as
			// it is the same
			if($i > 0) {
				$html .= '<tbody class="Skipped">';
				$html .= '<th>&hellip;</th>';
				$html .= '<th>&hellip;</th>';
				$html .= '<td>&#xA0;</td>';
				$html .= '</tbody>';
			}

			foreach($blocks as $change) {
				$html .= '<tbody class="Change'.ucfirst($change['tag']).'">';
				// Added lines only on the right side
				if($change['tag'] == 'insert') {
					foreach($change['changed']['lines'] as $no => $line) {
						$html .= '<tr>';
						$html .= '<td class="Right"><ins>'.$line.'</ins>&#xA0;</td>';
						$html .= '</tr>';
					}
				}
				// Show deleted lines only on the left side
				else if($change['tag'] == 'delete') {
					foreach($change['base']['lines'] as $no => $line) {
						$html .= '<tr>';
						$html .= '<td class="Left"><del>'.$line.'</del>&#xA0;</td>';
						$html .= '</tr>';
					}
				}
				// Show modified lines on both sides
				else if($change['tag'] == 'replace') {
					foreach($change['base']['lines'] as $no => $line) {
						$html .= '<tr>';
						$html .= '<td class="Left"><span>'.$line.'</span></td>';
						$html .= '</tr>';
					}

					foreach($change['changed']['lines'] as $no => $line) {
						$html .= '<tr>';
						$html .= '<td class="Right"><span>'.$line.'</span></td>';
						$html .= '</tr>';
					}
				}
				$html .= '</tbody>';
			}
		}
		$html .= '</table>';
		return $html;
	}
}
