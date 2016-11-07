INSERT INTO buttonset (id, name, sort_order) VALUES
(78, 'Steven Universe', 7600);

INSERT INTO button (id, name, recipe, btn_special, tourn_legal, set_id, flavor_text) VALUES
(707, 'Garnet', '(8) (8) (12) (20) r(4,4) r(4,4) r(6,6) r(10,10)', 0, 0, (SELECT id FROM buttonset WHERE name="Steven Universe"), 'Garnet is current leader of the Crystal Gems which she originally joined in order to live in a place where she was free to be herself. Garnet''s weapon is a pair of massive gauntlets ideal for beating people up.'),
(708, 'Amethyst', '(4) (6) (10) (16) rt(4) rt(6) rt(10) rt(16)', 0, 0, (SELECT id FROM buttonset WHERE name="Steven Universe"), 'Amethyst is a member of the Crystal Gems who was created in the Kindergarten on Earth. She has never been to the Gem Homeworld and considers the Earth to be her home. Amethyst can summon a multi-tailed whip with which to beat people up.'),
(709, 'Pearl (SU)', '(4) (6) (8) (12) rf(4) rf(6) rf(8) rf(12)', 0, 0, (SELECT id FROM buttonset WHERE name="Steven Universe"), 'Pearl is a member of the Crystal Gems. She was one of Rose Quartz''s closest followers in the rebellion against the Gem Homeworld and her sole confidant. Pearl wields a magic spear with a spiral blade that she uses to beat people up.'),
(710, 'Rose Quartz', '(8) (10) (16) (20) rM(8) rM(10) rM(16) rM(20)', 0, 0, (SELECT id FROM buttonset WHERE name="Steven Universe"), 'Rose Quartz was the founder and original leader of the Crystal Gems before she gave up her physical form to become Steven''s mother. She led her friends and allies in a rebellion against the Gem Homeworld over 5,000 years ago to protect the Earth from invasion. Rose Quartz was a natural leader, inspiring those around her to beat people up.'),
(711, 'Peridot', '(4) (8) (8) (12) ro(4) ro(8) ro(8) ro(12)', 0, 0, (SELECT id FROM buttonset WHERE name="Steven Universe"), 'Peridot is a Homeworld Gem technician and certified Kindergartener who became stranded on Earth and was forced to cooperate with the Crystal Gems. She prefers to wear limb enhancers to augment her height and reach, all the better to beat people up.'),
(712, 'Lapis Lazuli', '(4) (10) (10) (12) rD(4) rD(10) rD(10) rD(12)', 0, 0, (SELECT id FROM buttonset WHERE name="Steven Universe"), 'Lapis Lazuli is a Homeworld Gem who was trapped in a mirror for thousands of years until being freed by Steven. She does not trust the Crystal Gems or the Homeworld Gems. She has immense power over water, easily controlling it, reshaping it and using it to beat people up.'),
(713, 'Jasper', '(8) (12) (16) (20) rG(8) rG(12) rG(16) rG(20)', 0, 0, (SELECT id FROM buttonset WHERE name="Steven Universe"), 'Jasper is a Homeworld Gem who was a veteran of the rebellion and fought against Rose Quartz. She returned to Earth to retake it from the Crystal Gems. Jasper''s crash helmet is designed to be used for headbutting her enemies, her preferred way of beating people up.'),
(714, 'Steven Universe', '(6) M(6) (10) M(16) (X)!', 0, 0, (SELECT id FROM buttonset WHERE name="Steven Universe"), 'Steven Universe is a member of the Crystal Gems and the son of Rose Quartz and Greg Universe. He is the only human/gem hybrid in existence, and his full abilities and potential are yet to be discovered. Steven prefers to use his powers to defend and protect his friends with magical shields while they beat people up.'),
(715, 'Greg Universe', '(4) (8) (12) (20) (X)!', 0, 0, (SELECT id FROM buttonset WHERE name="Steven Universe"), 'Greg Universe, a.k.a. "Mr. Universe", is a human being, a retired rock musician and the father of Steven Universe. He owns the local car wash and lives out of a van because all of his money goes to support Steven and the Crystal Gems. He''s wary about "Gem business" but would do almost anything to support Steven, even beating people up.'),
(716, 'Connie', '(4) z(6) (10) z(12) (X)', 0, 0, (SELECT id FROM buttonset WHERE name="Steven Universe"), 'Connie Maheswaran is a human being and the best friend of Steven Universe. She is introverted, curious and intelligent. Connie has become adept at fighting with Rose Quartz''s sword, pairing with Steven in a complementary combination of defense and offense to beat people up.'),
(717, 'Lion (SU)', '(6) ^(10) (16) ^(U)', 0, 0, (SELECT id FROM buttonset WHERE name="Steven Universe"), 'Lion is a giant magical pink lion. Presumed to have originally been a creation of Rose Quartz, Steven now considers him to be his pet. Lion can create portals to travel across vast distances, has a pocket dimension hidden in his mane, and can use his roar to shatter objects and beat people up.');
