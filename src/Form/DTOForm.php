<?php

echo <<<EOT
    <form>
      <div class="form-group">
        <label for="competitionName">Competition name : </label>
        <input type="text" class="form-control" id="competitionName" placeholder="Name your competition">
      </div>
      <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="VisitorCheck">
        <label class="form-check-label" for="exampleCheck1">Visitor</label>
      </div>
      <div class="form-group">
        <label for="locationName">Location : </label>
        <input type="text" class="form-control" id="locationName" placeholder="Competition location">
      </div>
      <div class="form-group">
        <label for="tags">Tags : </label>
        <input type="text" class="form-control" id="tags">
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
EOT;
