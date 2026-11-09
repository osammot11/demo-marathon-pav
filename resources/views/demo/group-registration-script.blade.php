@once
<style>
.demo-group-person{border:1px solid #8886;padding:24px;margin:0 0 24px;border-radius:6px}.demo-group-heading{display:flex;justify-content:space-between;align-items:center;gap:16px}.demo-group-heading h3{margin:0 0 15px}.demo-group-heading button{background:transparent;color:inherit;border:1px solid #8888;padding:7px 12px;border-radius:4px;cursor:pointer}[data-demo-group] [hidden]{display:none!important}[data-demo-group] select{max-width:100%}[data-demo-group] [data-group-panel]{border:0;padding:0;margin:16px 0}[data-demo-group] .athlete-fields{min-width:0}[data-demo-group] .form-grid{min-width:0}@media(max-width:600px){.demo-group-person{padding:16px}.demo-group-heading{flex-wrap:wrap}}
</style>
<script>
document.addEventListener('DOMContentLoaded', () => {
 document.querySelectorAll('[data-demo-group]').forEach(form => {
  const list=form.querySelector('[data-group-people]'), template=form.querySelector('[data-person-template]'), add=form.querySelector('[data-group-add]');
  const max=Math.min(10,Number(form.dataset.max)||10), open=form.dataset.open==='1';
  const currency=new Intl.NumberFormat(document.documentElement.lang==='en'?'en-IE':'it-IT',{style:'currency',currency:'EUR'});
  function refresh(){
   const cards=[...list.querySelectorAll('[data-group-person]')]; let cents=0;
   cards.forEach((card,i)=>{
    card.querySelector('[data-group-number]').textContent=i+1;
    card.querySelectorAll('[name]').forEach(input=>input.name=input.name.replace(/participants\[(?:\d+|__INDEX__)\]/,`participants[${i}]`));
    card.querySelector('[data-group-remove]').hidden=cards.length===1;
    const selected=card.querySelector('[data-group-race]').value;
    card.querySelectorAll('[data-group-panel]').forEach(panel=>{
     const active=panel.dataset.groupPanel===selected; panel.hidden=!active; panel.disabled=!active||!open||panel.dataset.available!=='1';
     const membership=panel.querySelector('select[name$="[membership_type]"]'), number=panel.querySelector('input[name$="[membership_number]"]'), file=panel.querySelector('[data-medical-field][type=file]'), expiry=panel.querySelector('[data-medical-field][type=date]'), attest=panel.querySelector('[data-medical-attestation]');
     if(number) number.required=membership?.value!=='none';
     if(file){const required=!JSON.parse(panel.dataset.exempt).includes(membership?.value);file.required=required; if(expiry)expiry.required=required;if(attest)attest.required=required||file.files.length>0;}
     const price=Number(panel.dataset.price)+(membership?.value==='none'?Number(panel.dataset.surcharge):0);
     panel.querySelector('[data-group-price]').textContent=currency.format(price);
     if(active)cents+=Math.round(price*100);
    });
   });
   form.querySelector('[data-group-count]').textContent=cards.length; form.querySelector('[data-group-sum]').textContent=currency.format(cents/100);add.hidden=cards.length>=max;
  }
  add.addEventListener('click',()=>{if(!open||list.children.length>=max)return;const wrapper=document.createElement('div');wrapper.innerHTML=template.innerHTML.replaceAll('__INDEX__',String(list.children.length));list.append(wrapper.firstElementChild);refresh();list.lastElementChild.scrollIntoView({behavior:'smooth',block:'start'});});
  list.addEventListener('click',event=>{const remove=event.target.closest('[data-group-remove]');if(remove&&list.children.length>1){remove.closest('[data-group-person]').remove();refresh();}});
  list.addEventListener('change',refresh);refresh();
 });
});
</script>
@endonce
