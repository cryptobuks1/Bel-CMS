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
<div class="x_panel">
	<div class="x_title">
		<h2>Menu Page Team</h2>
		<div class="clearfix"></div>
	</div>
	<div class="x_content">
		<a href="/team?management&gaming=true" class="btn btn-app">
			<i class="fa fas fa-home"></i> Accueil
		</a>
		<a href="Team/parameter?management&gaming=true" class="btn btn-app">
			<i class="fa fas fa-cogs"></i> Configuration
		</a>
		<a href="/Team/addTeam?management&gaming=true" class="btn btn-app">
			<i class="fa fas fa-plus"></i> <?=ADD?>
		</a>
		<a class="btn btn-app">
			<span class="badge bg-red"><?=$count?></span>
			<i class="fa fa-bullhorn"></i> Team
		</a>
	</div>
</div>
<div class="col-md-12">
	<div class="panel panel-white">
		<div class="panel-body">
		   <div class="table-responsive">
				<table id="datatableTeam" class="table table-striped jambo_table bulk_action">
					<thead>
						<tr>
							<th># ID</th>
							<th>Nom</th>
							<th>Déscription</th>
							<th>Jeu</th>
							<th>NB° utilisateur</th>
							<th>Options</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th># ID</th>
							<th>Nom</th>
							<th>Déscription</th>
							<th>Jeu</th>
							<th>NB° utilisateur</th>
							<th>Options</th>
						</tr>
					</tfoot>
					<tbody>
					<?php
					foreach ($data as $k => $v):
						if (isset($v->game->name)) {
							$game = $v->game->name;
						} else {
							$game = 'Aucun jeu lié';
						}
						?>
						<tr>
							<td><?=$v->id?></td>
							<td><?=$v->name?></td>
							<td><?=$v->description?></td>
							<td><?=$game?></td>
							<td><?=$v->count?></td>
							<td>
								<a href="Team/player/<?=$v->id?>?management&gaming=true" class="btn btn-small btn-primary"> <i class="fas fa-user-plus"></i></a>
								<a href="team/edit/<?=$v->id?>?management&gaming=true" class="btn btn-small btn-success"><i class="fas fa-pen"></i></a>
								<a href="#" data-toggle="modal" data-target="#modal_<?=$v->id?>" class="btn btn-danger btn-small"><i class="fas fa-trash-alt"></i></a>
								<div class="modal fade" id="modal_<?=$v->id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title" id="exampleModalLabel"><?=$v->name?></h4>
											</div>
											<div class="modal-body">Confirmer la suppression de la team : <?=$v->name?></div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
												<button onclick="window.location.href='/team/del/<?=$v->id?>?management&gaming=true'" type="button" class="btn btn-primary">Supprimer</button>
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
</div>
<script src="/assets/plugins/jquery-3.3.1/jquery-3.3.1.min.js"></script>
<script src="/managements/assets/datatables/js/jquery.datatables.min.js"></script>
<script>
	(function($){
		$('#datatableTeam').dataTable( {
			"language": {
				"sProcessing":     "Traitement en cours...",
				"sSearch":         "Rechercher&nbsp;:",
				"sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
				"sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
				"sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
				"sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
				"sInfoPostFix":    "",
				"sLoadingRecords": "Chargement en cours...",
				"sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
				"sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
				"oPaginate": {
					"sFirst":      "Premier",
					"sPrevious":   "Pr&eacute;c&eacute;dent",
					"sNext":       "Suivant",
					"sLast":       "Dernier"
				},
				"oAria": {
					"sSortAscending":  ": activer pour trier la colonne par ordre croissant",
					"sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
				},
				"select": {
						"rows": {
							_: "%d lignes séléctionnées",
							0: "Aucune ligne séléctionnée",
							1: "1 ligne séléctionnée"
						} 
				}
			}
		} );
	})(jQuery);
</script>