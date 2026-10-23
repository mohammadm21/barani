<?php
check_session();
$limit = isset($_POST['group_no']) ? safe::number($_POST['group_no']) : die('group_no?');
$row = isset($_POST['row']) ? safe::number($_POST['row']) : die('row?');

$limit = ($limit * 30);
$x = $row;

$sql->join('user u', 'u.id=p.id', 'LEFT');
$sql->where("u.type", 1, '!=');
$sql->orderBy('resualt', 'DESC');
$sql->orderBy('like', 'DESC');
foreach($sql->get('profiles p', array($limit, 30), array(
    'u.id',
    'u.name',
    'u.type',
    'p.like',
    'p.dislike',
    'p.avatar',
    '(p.`like` - p.`dislike`) AS resualt'
)) as $row){
    ?> 
    <tr>
        <td><?= $x; ?></td>
        <td><a target="_blank" href="/profile/<?= $row['id']; ?>"><img src="/<?= $options['avatar_dir'] . '/' . MiniAvatarDir() . '/' . $row['avatar']; ?>"></a></td>
        <td><a target="_blank" href="/profile/<?= $row['id']; ?>"><font color="<?= $usertype[$row['type']]['color']; ?>"><?= $row['name']; ?></font></a></td>
        <td><img src="/<?= $usertype[$row['type']]['icon']; ?>"></td>
        <td><?= $row['like']; ?></td>
        <td><?= $row['dislike']; ?></td>
        <td><?= $row['resualt']; ?></td>
    </tr>
    <?php
    $x++;
}

?>