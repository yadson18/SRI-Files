<?php  
	namespace Simple\Controller\Components;

	class PaginatorComponent
	{
		private $listQuantity;

		private $totalQuantity = 50;

		private $currentPage = 1;

		private $buttonsQuantityShown = 8;

		private $pageLink;

		public function showPage(int $page)
		{
			$this->setCurrentPage($page);

			return $this;
		}

		public function buttonsLink(string $link)
		{
			$this->setButtonLink($link);

			return $this;
		}

		public function itensTotalQuantity(int $total)
		{
			$this->setTotalQuantity($total);

			return $this;
		}

		public function limit(int $quantity)
		{
			$this->setListQuantity($quantity);

			return $this;
		}

		public function display()
		{
			if ($this->getCurrentPage() <= $this->getTotalPages()) {
				return $this->text() . $this->buttons();
			}
		}

		protected function createPaginateBtn($name, $value, bool $active = false)
		{
			$class = ($active === true) ? 'active' : '';
			$href = ($active !== true) ? 'href="' . $this->getButtonLink() . $value . '"' : '';

			return '<li class="' . $class  . '">
						<a ' . $href . '>' . $name . '</a>
					</li>';
		}

		protected function buttons()
		{
			$buttonsQuantity = $this->getButtonsQuantityShown();
			$currentPage = $this->getCurrentPage();
			$totalPages = $this->getTotalPages();
			$buttonsPaginator = '';

			if ($totalPages > 1) {
				$buttonsPaginator .= $this->createPaginateBtn(
					($totalPages > $buttonsQuantity) ? 'Início' : 1, 1, 
					($currentPage === 1) ? true : false
				);

				if ($totalPages > $buttonsQuantity) {
					if ($currentPage < $buttonsQuantity) {
						$before = 1;
						$after = $buttonsQuantity + 1;
					}
					else if ($currentPage >= $buttonsQuantity) {
						$before = $currentPage - ($buttonsQuantity - 2);

						if (($currentPage + 1) < $totalPages) {
							$after = $currentPage + 1;
						}
						else if (($currentPage + 1) > $totalPages) {
							$before = $currentPage - $buttonsQuantity;
							$after = $currentPage;
						}
						else {
							$before = $currentPage - ($buttonsQuantity - 1);
							$after = $currentPage;
						}
					}
				}
				else {
					$before = 1;
					$after = $totalPages;
				}

				for ($page = $before; $page <= $after; $page++) { 
					if ($page !== 1 && $page !== $totalPages) {
						$buttonsPaginator .= $this->createPaginateBtn(
							$page, $page, ($page === $currentPage) ? true : false
						);
					}
				}
				$buttonsPaginator .= $this->createPaginateBtn(
					($totalPages > $buttonsQuantity) ? 'Fim' : $totalPages, $totalPages, 
					($currentPage === $totalPages) ? true : false
				);

				return '<ul class="pagination pull-right">' . $buttonsPaginator . '</ul>';
			}
		}

		protected function text()
		{
			return '<div class="pull-right list-shown">
				<p>
					Página <strong class="thousand">' . $this->getCurrentPage() . '</strong>
					de <strong class="thousand">' . $this->getTotalPages() . '</strong>,
					listando <strong class="thousand shown">' . 
						$this->getShownQuantity() . 
					'</strong>
					itens de <strong class="thousand quantity">' . 
						$this->getTotalQuantity() . 
					'</strong>.
				</p>
			</div>';
		}

		protected function setButtonLink(string $link)
		{
			$this->pageLink = $link;
		}

		public function getButtonLink()
		{
			return $this->pageLink;
		}

		protected function setButtonsQuantityShown(int $quantity)
		{
			$this->buttonsQuantityShown = $quantity;
		}

		public function getButtonsQuantityShown()
		{
			return $this->buttonsQuantityShown;
		}

		protected function setListQuantity(int $quantity)
		{
			$this->listQuantity = $quantity;
		}

		public function getListQuantity()
		{
			return $this->listQuantity;
		}

		protected function setCurrentPage(int $page)
		{
			if (strlen($page) <= 8) {
				$this->currentPage = $page;
			}
		}

		public function getCurrentPage()
		{
			return $this->currentPage;
		}

		protected function setTotalQuantity(int $total)
		{
			$this->totalQuantity = $total;
		}

		public function getTotalQuantity()
		{
			return $this->totalQuantity;
		}

		public function getStartPosition()
		{
			$startPosition = $this->getListQuantity() * ($this->getCurrentPage() - 1);

			return ($startPosition > 0) ? $startPosition : 0;
		}

		public function getShownQuantity()
		{
			$shownQuantity = $this->getCurrentPage() * $this->getListQuantity();

			if ($shownQuantity > $this->getTotalQuantity()) {
				return $this->getTotalQuantity();
			}
			return $shownQuantity;
		}

		public function getTotalPages()
		{
			return (int) ceil(
				$this->getTotalQuantity() / $this->getListQuantity()
			);
		}
	}