@if(setting('enable_creator_ads') ?? true)
  <div class="card-footer text-muted">
    <span>Use the creator code RECURSIVETREE during checkout on https://store.eveonline.com to support the development of this plugin with up to 5% of your purchase.</span>
    <a href="{{route("treelib.disableCreatorCodeAdvertisment")}}" class="text-muted ml-2 confirmlink">Hide</a>
  </div>
@endif