<?php
function front_qa_plugin()
{
    ob_start(); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
          integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <div class="container-fluid mt-4">
		<h1 class="mb-5" style="color:#16794A">QA Plugin</h1>
		
		<div class="alert alert-success" style="display: none" id="alert" role="alert">
 			Your comment has been saved!
		</div>
		 <div class="d-flex justify-content-end mt-5 mb-3">
			 	<button type="button" id="clearBtn" class="btn btn-secondary">
					Clear all<i class=" ms-3 fas fa-broom"></i>
				</button>
           		 <button class="ms-3 btn btn-success" data-bs-toggle="modal"
                 		 data-bs-target="#newCheckboxModal">Add new checkbox<i class="ms-3 fas fa-plus"></i></button>
        </div>
        <?php $data = get_qa_requests();?>
        <table class="table table-striped table-borderless" id="table">
            <thead>
            <tr class="table-success">
                <th scope="col"></th>
                <th scope="col">TITLE</th>
                <th scope="col">DESCRIPTION</th>
                <th scope="col">COMMENT</th>
				<th scope="col">HISTORY</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $row): ?>
				<?php $checked = ''; $history = get_history($row->id);?>
				<?php if($row->checked == 1) 
				{
					$checked = 'checked';
				}?>
				<?php $historyText = ''?>
				<?php if($history != null ): ?>
				<?php foreach($history as $i => $hisRow): ?>
				<?php $historyText .= ($hisRow->checked == 1) ? ($i+1).'. '.$hisRow->username.' has checked </br>' : ($i+1).'. '.$hisRow->username.' has unchecked </br>';?>
				<?php endforeach; ?>
				<?php endif; ?>
                <tr name="tableRow" id="<?= $row->id ?>">
                    <td><input class="form-input qa-checkbox" name="check" type="checkbox" value="<?= wp_get_current_user()->display_name?>" id="<?= $row->id ?>" id="defaultCheck1" <?= $checked ?> ></td>
                    <td><?= $row->title ?></td>
                    <td><?= $row->description ?></td>
                    <td><textarea class="qa-comment" value="<?= $row->comment ?>" id="<?= $row->id ?>"><?= $row->comment ?></textarea></td>
					<td class="qa-history" id="history<?=$row->id?>"><?= $historyText?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
           		 <button class="ms-3 btn btn-success" data-bs-toggle="modal"
                   		 data-bs-target="#newCheckboxModal">Add new checkbox<i class="ms-3 fas fa-plus"></i></button>
        </div>

    </div>


    <!-- Modal -->
    <div class="modal fade" id="newCheckboxModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="color: #198754">Add new QA checkbox</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
					<form id="modalForm" >
						<div class="container mt-3">
							<label class="form-label fw-bold">QA request name: </label>
							<input type="text" class="form-control" id="qa_request_name" required>
						</div>
						<div class="container mt-4 mb-3">
							<label class="form-label fw-bold">QA request description:  </label>
							<input type="text" class="form-control" id="qa_request_desc">
						</div>
					<div class="modal-footer container">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" id="submitBtn" class="btn btn-success" data-bs-dismiss="modal" disabled>Save changes</button>
					</div>
				</form>
            </div>
        </div>
    </div>
    <script src="/wp-content/plugins/qa_plugin/script.js"></script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
    <?php
    echo ob_get_clean();
}