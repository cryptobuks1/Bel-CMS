<?php
/**
 * Bel-CMS [Content management system]
 * @version 1.0.0
 * @link https://bel-cms.be
 * @link https://determe.be
 * @license http://opensource.org/licenses/GPL-3.-copyleft
 * @copyright 2014-2019 Bel-CMS
 * @author as Stive - stive@determe.be
 */

if (!defined('CHECK_INDEX')) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 403 Direct access forbidden');
	exit(ERROR_INDEX);
}
?>
<div class="row">
	<div class="col-lg-4 col-md-12 col-sm-12">
		<div class="card">
			<div class="list-group list-group-transparent mb-0 mail-inbox">
				<div class="mt-4 mb-4 ml-4 mr-4 text-center">
					<a href="/downloads/add?management&page=true" class="btn btn-primary btn-lg btn-block"><?=ADD?></a>
				</div>
				<a href="/downloads?management&page=true" class="list-group-item list-group-item-action d-flex align-items-center active">
					<span class="icon mr-3"><i class="fa fas fa-home"></i></span>Accueil
				</a>
				<a href="/downloads/cat?management&page=truee" class="list-group-item list-group-item-action d-flex align-items-center">
					<span class="icon mr-3"><i class="fa fas fa-cogs"></i></span><?=CATEGORY?>
				</a>
				<a href="/downloads/parameter?management&page=true" class="list-group-item list-group-item-action d-flex align-items-center">
					<span class="icon mr-3"><i class="fa fas fa-cogs"></i></span>Configuration
				</a>
			</div>
		</div>
	</div>

	<div class="col-lg-8 col-md-12 col-sm-12">
		<div class="card">
			<table id="datatableDl" class="table table-striped jambo_table bulk_action">
				<thead>
					<tr>
						<th># ID</th>
						<th>Nom</th>
						<th>Date de publication</th>
						<th>Auteur</th>
						<th>Options</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th># ID</th>
						<th>Nom</th>
						<th>Date de publication</th>
						<th>Auteur</th>
						<th>Options</th>
					</tr>
				</tfoot>
				<tbody>
					<?php
					foreach ($data as $k => $v):
						?>
						<tr>
							<td><?=$v->id?></td>
							<td><?=$v->name?></td>
							<td><?=Common::TransformDate($v->date, 'MEDIUM', 'SHORT')?></td>
							<td><?=Users::hashkeyToUsernameAvatar($v->uploader)?></td>
							<td>
								<a href="blog/edit/<?=$v->id?>?management&page=true"><i class="fas fa-pen"></i></a> - 
								<a href="#" data-toggle="modal" data-target="#modal_<?=$v->id?>"><i class="fas fa-trash-alt"></i></a>
								<div class="modal fade" id="modal_<?=$v->id?>" tabindex="-1" role="dialog" aria-labelledby="DownloadsModalLabel">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title" id="DownloadsModalLabel"><?=$v->name?></h4>
											</div>
											<div class="modal-body">Confirmer la suppression du fichier : <?=$v->name?></div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
												<button onclick="window.location.href='/downloads/del/<?=$v->id?>?management&page=true'" type="button" class="btn btn-primary">Supprimer</button>
											</div>
										</div>
									</div>
								</div>
							</td>
						</tr>
						<?php
					endforeach;
					?>
				</tbody>
			</table>  
		</div>
	</div>
</div>